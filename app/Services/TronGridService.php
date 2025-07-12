<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Mdanter\Ecc\EccFactory;
use Mdanter\Ecc\Crypto\Key\PrivateKeyInterface;
use kornrunner\Keccak;

class TronGridService
{
    protected string $nodeUrl;

    public function __construct()
    {
        $this->nodeUrl = config('services.tron.node_url', env('TRON_NODE_URL'));
    }

    // 1. Generate new wallet (private/public/address)
    public function generateWallet(): array
    {
        $generator = EccFactory::getSecgCurves()->generator256k1();
        $privateKey = $generator->createPrivateKey();
        $publicKey = $privateKey->getPublicKey();
        $publicKeyHex = '04' .
            str_pad(gmp_strval($publicKey->getPoint()->getX(), 16), 64, '0', STR_PAD_LEFT) .
            str_pad(gmp_strval($publicKey->getPoint()->getY(), 16), 64, '0', STR_PAD_LEFT);

        $keccak = Keccak::hash(hex2bin(substr($publicKeyHex, 2)), 256);
        $addressHex = '41' . substr($keccak, -40);
        $addressBase58 = $this->base58CheckEncode($addressHex);

        return [
            'private_key' => $privateKey->getSecret(), // GMP
            'private_key_hex' => str_pad(gmp_strval($privateKey->getSecret(), 16), 64, '0', STR_PAD_LEFT),
            'public_key' => $publicKeyHex,
            'address' => $addressBase58,
            'address_hex' => $addressHex
        ];
    }

    // 2. Send TRX
    public function sendTrx(string $fromAddress, string $toAddress, float $amountTrx, string $privateKeyHex)
    {
        try {
            $amountSun = intval($amountTrx * 1_000_000);
            $response = Http::post("{$this->nodeUrl}/wallet/createtransaction", [
                'owner_address' => $this->base58ToHex($fromAddress),
                'to_address' => $this->base58ToHex($toAddress),
                'amount' => $amountSun,
            ]);

            $transaction = $response->json();

            if (isset($transaction['Error'])) {
                throw new \Exception("Creation error: {$transaction['Error']}");
            }

            $signed = $this->signTransaction($transaction, $privateKeyHex);

            $broadcast = Http::post("{$this->nodeUrl}/wallet/broadcasttransaction", $signed);
            $result = $broadcast->json();

            if (!($result['result'] ?? false)) {
                throw new \Exception("Broadcast failed: " . json_encode($result));
            }

            return $result;
        } catch (\Throwable $e) {
            return ['error' => $e->getMessage()];
        }
    }

    // 3. Freeze TRX for BANDWIDTH or ENERGY
    public function freezeBalance(string $ownerAddress, int $amountSun, int $duration = 3, string $resource = 'BANDWIDTH', string $privateKeyHex)
    {
        $tx = Http::post("{$this->nodeUrl}/wallet/freezebalance", [
            'owner_address' => $this->base58ToHex($ownerAddress),
            'frozen_balance' => $amountSun,
            'frozen_duration' => $duration,
            'resource' => strtoupper($resource),
        ])->json();

        $signed = $this->signTransaction($tx, $privateKeyHex);

        return Http::post("{$this->nodeUrl}/wallet/broadcasttransaction", $signed)->json();
    }

    // 4. Unfreeze
    public function unfreezeBalance(string $ownerAddress, string $resource = 'BANDWIDTH', string $privateKeyHex)
    {
        $tx = Http::post("{$this->nodeUrl}/wallet/unfreezebalance", [
            'owner_address' => $this->base58ToHex($ownerAddress),
            'resource' => strtoupper($resource),
        ])->json();

        $signed = $this->signTransaction($tx, $privateKeyHex);

        return Http::post("{$this->nodeUrl}/wallet/broadcasttransaction", $signed)->json();
    }

    // 5. Get balance
    public function getBalance(string $address)
    {
        $res = Http::post("{$this->nodeUrl}/wallet/getaccount", [
            'address' => $this->base58ToHex($address),
        ])->json();

        return [
            'balance_trx' => isset($res['balance']) ? $res['balance'] / 1_000_000 : 0,
            'bandwidth' => $res['free_net_limit'] ?? 0,
        ];
    }

    // 6. Get transaction status
    public function getTransactionStatus(string $txid)
    {
        return Http::post("{$this->nodeUrl}/wallet/gettransactionbyid", ['value' => $txid])->json();
    }

    // Helper: sign transaction
    public function signTransaction(array $transaction, string $privateKeyHex)
    {
        $rawData = $transaction['raw_data_hex'];
        $hash = Keccak::hash(hex2bin($rawData), 256);
        $signature = $this->signHex($hash, $privateKeyHex);
        $transaction['signature'] = [$signature];
        return $transaction;
    }

    protected function signHex(string $hashHex, string $privateKeyHex): string
    {
        $secp = EccFactory::getSecgCurves()->generator256k1();
        $adapter = EccFactory::getAdapter();
        $privateKey = $secp->getPrivateKeyFrom(gmp_init($privateKeyHex, 16));
        $signer = EccFactory::getSigner();
        $signature = $signer->sign($privateKey, gmp_init($hashHex, 16));
        return implode('', [
            str_pad(gmp_strval($signature->getR(), 16), 64, '0', STR_PAD_LEFT),
            str_pad(gmp_strval($signature->getS(), 16), 64, '0', STR_PAD_LEFT)
        ]);
    }

    // Address helpers
    public function base58ToHex(string $address): string
    {
        $alphabet = '123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz';
        $decoded = 0;
        for ($i = 0; $i < strlen($address); $i++) {
            $decoded = bcmul($decoded, '58');
            $decoded = bcadd($decoded, (string) strpos($alphabet, $address[$i]));
        }
        return strtoupper(substr(str_pad(gmp_strval($decoded, 16), 50, '0', STR_PAD_LEFT), 0, 42));
    }

    public function base58CheckEncode(string $hex): string
    {
        $data = hex2bin($hex);
        $hash0 = hash('sha256', $data, true);
        $hash1 = hash('sha256', $hash0, true);
        $checksum = substr($hash1, 0, 4);
        return $this->base58Encode($data . $checksum);
    }

    public function base58Encode(string $input): string
    {
        $alphabet = '123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz';
        $intVal = gmp_init(bin2hex($input), 16);
        $output = '';
        while (gmp_cmp($intVal, 0) > 0) {
            list($intVal, $rem) = gmp_div_qr($intVal, 58);
            $output = $alphabet[gmp_intval($rem)] . $output;
        }
        foreach (str_split($input) as $char) {
            if ($char === "\x00") $output = $alphabet[0] . $output;
            else break;
        }
        return $output;
    }
}
