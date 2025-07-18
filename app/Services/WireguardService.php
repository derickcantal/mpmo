<?php

namespace App\Services;

use App\Models\VpnClient;
use Symfony\Component\Process\Process;

class WireguardService
{
    protected $serverPublicKey;
    protected $endpoint;
    protected $dns;
    protected $wgBin = '/usr/bin/wg'; 

    public function __construct()
    {
        $this->serverPublicKey = config('wireguard.server_public_key');
        $this->endpoint        = config('wireguard.endpoint','31.97.61.228');       // e.g. 31.97.61.228:51820
        $this->dns             = config('wireguard.dns', '1.1.1.1');
    }

    public function createClient(string $name, string $address): VpnClient
    {
         // 1. Generate private key
        $prv = new Process([$this->wgBin, 'genkey']);
        $prv->run();
        if (! $prv->isSuccessful()) {
            throw new \RuntimeException(
                'wg genkey failed: ' . $prv->getErrorOutput()
            );
        }
        $privateKey = trim($prv->getOutput());

        // 2. Derive public key
        $pub = new Process([$this->wgBin, 'pubkey']);
        $pub->setInput($privateKey);
        $pub->run();
        if (! $pub->isSuccessful()) {
            throw new \RuntimeException(
                'wg pubkey failed: ' . $pub->getErrorOutput()
            );
        }
        $publicKey = trim($pub->getOutput());

        // 3. Save to database (private_key is cast→encrypted)
        return VpnClient::create(compact('name','publicKey','privateKey','address'));
    }

    public function clientConfig(VpnClient $client): string
    {
        return <<<EOT
        [Interface]
        PrivateKey = {$client->private_key}
        Address    = {$client->address}
        DNS        = {$this->dns}

        [Peer]
        PublicKey           = {$this->serverPublicKey}
        Endpoint            = {$this->endpoint}
        AllowedIPs          = 0.0.0.0/0
        PersistentKeepalive = 25
        EOT;
    }

    public function clientQr(VpnClient $client): string
    {
        $conf = $this->clientConfig($client);

        $result = \Endroid\QrCode\Builder\Builder::create()
            ->data($conf)
            ->size(300)
            ->margin(10)
            ->build();

        return $result->getDataUri();  // data:image/png;base64,...
    }
}
