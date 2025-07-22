<?php

namespace App\Services;

use App\Models\VpnClient;
use Symfony\Component\Process\Process;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\WebPWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\QrCodeInterface;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Builder\Builder;

class WireguardService
{
    protected $serverPublicKey;
    protected $endpoint;
    protected $dns;
    protected $wgBin = '/usr/bin/wg'; 

    public function __construct()
    {
        $this->serverPublicKey = config('wireguard.server_public_key','yjU1vABHDd8NMpaK//yH80nwg0pm5ZCtP7HtZN9n8xM=');
        $this->endpoint        = config('wireguard.endpoint','31.97.61.228:51820');       // e.g. 31.97.61.228:51820
        $this->dns             = config('wireguard.dns', '1.1.1.1');
    }

    public function addPeer(VpnClient $client): void
    {
        $proc = new Process([
            'sudo','-n','/usr/bin/wg','set','wg0',
            'peer', $client->public_key,
            'allowed-ips', $client->address,
        ]);
        $proc->run();
        if (! $proc->isSuccessful()) {
            throw new \RuntimeException("wg set failed: " . $proc->getErrorOutput());
        }
    }
    
    public function createClient(string $name, string $address): VpnClient
    {
        // 1. Generate keys
        $prvProc = new Process(['wg','genkey']);
        $prvProc->run();
        $privateKey = trim($prvProc->getOutput());

        $pubProc = new Process(['wg','pubkey']);
        $pubProc->setInput($privateKey);
        $pubProc->run();
        $publicKey = trim($pubProc->getOutput());

        // 2. Write them to a directory you own (e.g. /etc/wireguard/clients)
        // $dir = '/etc/wireguard/clients';
        // if (! is_dir($dir)) {
        //     mkdir($dir, 0700, true);
        //     chown($dir, 'www-data');  // or whatever your PHP user is
        // }

        $dir = storage_path('app/wireguard');
        if (! is_dir($dir)) {
            mkdir($dir, 0700, true);  // always works under storage/
        }
        $base = "$dir/{$address}";  // or use $name, or an incremental ID
        file_put_contents("{$base}.key", $privateKey);
        chmod("{$base}.key", 0600);
        file_put_contents("{$base}.pub", $publicKey);
        chmod("{$base}.pub", 0644);

        // 3. Persist in the database
        $client = VpnClient::create([
            'name'        => $name,
            'public_key'  => $publicKey,
            'private_key' => $privateKey,
            'address'     => $address,
        ]);

        // 4. Inject into WireGuard (and persist to wg0.conf)
        $this->addPeer($client);

        return $client;
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

        $qr = new QrCode(
            data: $conf,
            encoding: new Encoding('UTF-8'),
            size: 300,
            margin: 10
        );

        $writer = new WebPWriter();
        $result = $writer->write($qr);

        $client->update([
            'qr_code' => $result->getString(),
        ]);

        return $result->getDataUri();
    }
}
