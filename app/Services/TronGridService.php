<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Mdanter\Ecc\EccFactory;
use Mdanter\Ecc\Crypto\Key\PrivateKeyInterface;
use kornrunner\Keccak;

class TronGridService
{
    protected $baseUrl = 'https://api.trongrid.io/';
    protected $http;
    protected $generator;

    public function __construct()
    {
        $this->http = Http::baseUrl($this->baseUrl);
        $adapter = EccFactory::getAdapter();
        $this->generator = EccFactory::getSecgCurves($adapter)->generator256k1();
    }

    /**
     * Create new wallet (private key, public key, address)
     * @return array
     */
    public function createWallet()
    {
        /** @var PrivateKeyInterface $privateKey */
        $privateKey = $this->generator->createPrivateKey();
        $publicKey = $privateKey->getPublicKey();

        $privateKeyHex = str_pad(gmp_strval($privateKey->getSecret(), 16), 64, '0', STR_PAD_LEFT);
        $publicKeyHex = $this->encodePublicKey($publicKey);

        $address = $this->publicKeyToAddress($publicKeyHex);

        return [
            'privateKey' => $privateKeyHex,
            'publicKey' => $publicKeyHex,
            'address' => $address,
        ];
    }

    /**
     * Encode public key (uncompressed) as hex string 04 + X + Y
     */
    protected function encodePublicKey($publicKey)
    {
        $x = str_pad(gmp_strval($publicKey->getPoint()->getX(), 16), 64, '0', STR_PAD_LEFT);
        $y = str_pad(gmp_strval($publicKey->getPoint()->getY(), 16), 64, '0', STR_PAD_LEFT);
        return '04' . $x . $y;
    }

    /**
     * Convert public key hex to Tron base58 address
     */
    protected function publicKeyToAddress($publicKeyHex)
    {
        $publicKeyBin = hex2bin($publicKeyHex);
        $hash = Keccak::hash(substr($publicKeyBin, 1), 256);
        $addressHex = '41' . substr($hash, 24);
        return $this->base58CheckEncode($addressHex);
    }

    /**
     * Base58Check encode with checksum
     */
    protected function base58CheckEncode($hex)
    {
        $alphabet = '123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz';
        $base = strlen($alphabet);

        $hexBytes = hex2bin($hex);
        $checksum = substr(hash('sha256', hash('sha256', $hexBytes, true), true), 0, 4);
        $hexWithChecksum = $hexBytes . $checksum;
        $num = gmp_init(bin2hex($hexWithChecksum), 16);

        $encoded = '';
        while (gmp_cmp($num, 0) > 0) {
            list($num, $rem) = gmp_div_qr($num, $base);
            $encoded = $alphabet[gmp_intval($rem)] . $encoded;
        }

        // Leading zeros handled as '1's
        foreach (str_split($hexWithChecksum) as $byte) {
            if ($byte === "\x00") {
                $encoded = '1' . $encoded;
            } else {
                break;
            }
        }

        return $encoded;
    }

    /**
     * Get balance of an address in TRX
     */
    public function getBalance($address)
    {
        $response = $this->http->get("v1/accounts/{$address}");
        $data = $response->json();

        if (!empty($data['data'][0]['balance'])) {
            return $data['data'][0]['balance'] / 1_000_000;
        }
        return 0;
    }

    /**
     * Send TRX from private key wallet to recipient
     */
    public function sendTrx(string $privateKeyHex, string $toAddressBase58, float $amountTrx)
    {
        $amountSun = (int)round($amountTrx * 1_000_000);

        // Derive owner address hex from private key
        $publicKeyHex = $this->publicKeyFromPrivateKey($privateKeyHex);
        $ownerAddressHex = $this->base58ToHex($this->publicKeyToAddress($publicKeyHex));
        $toAddressHex = $this->base58ToHex($toAddressBase58);

        // Step 1: Create transaction
        $createTxResp = $this->http->post('wallet/createtransaction', [
            'to_address' => $toAddressHex,
            'owner_address' => $ownerAddressHex,
            'amount' => $amountSun,
        ]);

        $tx = $createTxResp->json();

        if (!isset($tx['txID'])) {
            return ['success' => false, 'message' => 'Failed to create transaction'];
        }

        // Step 2: Sign transaction
        $signedTx = $this->signTransaction($tx, $privateKeyHex);

        // Step 3: Broadcast transaction
        $broadcastResp = $this->http->post('wallet/broadcasttransaction', $signedTx);
        $broadcast = $broadcastResp->json();

        if (!empty($broadcast['result']) && $broadcast['result'] === true) {
            return ['success' => true, 'tx_hash' => $tx['txID']];
        }

        return ['success' => false, 'message' => 'Broadcast failed', 'details' => $broadcast];
    }

    /**
     * Sign the transaction raw_data_hex with private key
     */
    protected function signTransaction(array $transaction, string $privateKeyHex)
    {
        $rawDataHex = $transaction['raw_data_hex'] ?? null;
        if (!$rawDataHex) {
            throw new \Exception('Transaction raw_data_hex missing');
        }

        $rawDataBin = hex2bin($rawDataHex);
        $hash = hash('sha256', $rawDataBin, true);

        $signature = $this->signHash($hash, $privateKeyHex);

        $transaction['signature'] = [$signature];

        return $transaction;
    }

    /**
     * Sign a 32-byte hash using secp256k1 private key
     */
    protected function signHash(string $hash, string $privateKeyHex)
    {
        $adapter = EccFactory::getAdapter();
        $privateKey = $this->generator->getPrivateKey(gmp_init($privateKeyHex, 16));
        $signer = EccFactory::getSigner();

        $signature = $signer->sign($privateKey, $hash);

        // DER encode the signature
        return bin2hex($signature->toDer());
    }

    /**
     * Derive public key hex from private key hex
     */
    protected function publicKeyFromPrivateKey(string $privateKeyHex): string
    {
        $privateKey = $this->generator->getPrivateKey(gmp_init($privateKeyHex, 16));
        $publicKey = $privateKey->getPublicKey();

        return $this->encodePublicKey($publicKey);
    }

    /**
     * Convert base58 address to hex (needed for tron API)
     */
    protected function base58ToHex(string $base58): string
    {
        $alphabet = '123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz';
        $base = strlen($alphabet);
        $num = gmp_init(0);

        $length = strlen($base58);
        for ($i = 0; $i < $length; $i++) {
            $pos = strpos($alphabet, $base58[$i]);
            $num = gmp_add(gmp_mul($num, $base), $pos);
        }

        $hex = gmp_strval($num, 16);

        return str_pad($hex, 42, '0', STR_PAD_LEFT);
    }
}