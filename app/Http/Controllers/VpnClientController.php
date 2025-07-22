<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VpnClient;
use App\Services\WireguardService;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\WebPWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\QrCodeInterface;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Builder\Builder;


class VpnClientController extends Controller
{
    public function index()
    {
        return view('vpn.index', ['clients' => VpnClient::all()]);
    }

    public function store(Request $request, WireguardService $wg)
    {
        $data = $request->validate([
            'name'    => 'required|string',
            'address' => 'required|ip|unique:vpn_clients,address',
        ]);

        // 1) Create & persist in one step
        $client = $wg->createClient(
            $request->input('name'),
            $request->input('address')
        );

        // 1) Create & persist the client
        $client = $wg->createClient($data['name'], $data['address']);

        // 2) Inject and save the peer on the server
        $wg->addPeer($client);

        return redirect()->route('vpn.show', $client->id);
    }

    // Add this method:
    public function create()
    {
        return view('vpn.create');
    }

    public function show(VpnClient $vpnid, WireguardService $wg)
    {
        // dd($vpnid);
        $vpnClient = $vpnid;
        $qrDataUri = $wg->clientQr($vpnClient);
        $conf      = $wg->clientConfig($vpnClient);

        return view('vpn.show', compact('vpnClient','qrDataUri','conf'));
    }
}
