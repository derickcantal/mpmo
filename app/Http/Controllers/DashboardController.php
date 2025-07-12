<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\cwallet;
use App\Models\transactions;
use App\Services\TronGridService;
use Illuminate\Support\Facades\Auth;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\WebPWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\QrCodeInterface;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\Label\Label;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;



class DashboardController extends Controller
{
    protected $tron;

    public function __construct(TronGridService $tron)
    {
        $this->tron = $tron;
    }

    public function index()
    {
        if(auth()->user()->email != 'admin@mypocketmonster.net')
        {
            return redirect()->back()->with('failed', 'Access not Allowed!');
        }
        $wallets = Auth::user()->wallets()->get();
        foreach ($wallets as $wallet) {
            $wallet->balance = $this->tron->getBalance($wallet->cwaddress);
        }
        return view('wallets.index', compact('wallets'));
    }

    public function createWallet()
    {
        return redirect()->back()->with('failed', 'Wallet Create Disabled!');

        $data = 'TTMXREjCY9MJmy1YzNnwSZhk9tCSV2X9Pp';

        $qrCode = new QrCode(
            data: $data,
            encoding: new Encoding('UTF-8'),
            size: 400,
            margin: 10
        );

        $writer = new WebPWriter();
        $result = $writer->write($qrCode);
        $webpData = $result->getString();

        $manager = ImageManager::imagick();

        $qrImage = $manager->read($webpData);

        $logoImage = $manager->read(public_path('storage/img/logo.png'));

        $logoSize = intval($qrImage->width() * 0.25);
        $logoImage = $logoImage->scale(width: $logoSize);

        $qrImage = $qrImage->place($logoImage, 'center');

        $finalWebp = (string) $qrImage->toWebp(80);

        $filename = 'qrcodes/' . hexdec(uniqid()) . '.webp';
        Storage::disk('public')->put($filename, $finalWebp);


        $walletData = $this->tron->createWallet();
        cwallet::create([
            'userid' => Auth::user()->userid,
            'address' => $walletData['address'],
            'private_key' => encrypt($walletData['privateKey']),
            'public_key' => $walletData['publicKey'],
        ]);

        return redirect()->route('dashboard')->with('success', 'Wallet created!');
    }

    public function showSendForm($address)
    {
        // dd($address);
        return view('wallets.send', compact('address'));
    }

  public function send(Request $request)
    {
        // 1) Validate input
        $data = $request->validate([
            'address'    => 'required|string',           // your wallet address field
            'to_address' => 'required|string',
            'amount'     => 'required|numeric|min:0.000001',
        ]);

        // 2) Lookup the wallet for the logged-in user
        $wallet = Auth::user()
            ->wallets()
            ->where('cwaddress', $data['address'])
            ->firstOrFail();

        // 3) Call sendTrx: pass (from, to, amount, encrypted private key)
        $result = $this->tron->sendTrx(
            $wallet->cwaddress,
            $data['to_address'],
            (float) $data['amount'],
            $wallet->private_key  // encrypted decimal secret from DB
        );

        // 4) Handle response
        if (! empty($result['success']) && $result['success'] === true) {
            // Persist transaction record
            Transaction::create([
                'cwid'      => $wallet->cwid,
                'to_address'=> $data['to_address'],
                'amount'    => $data['amount'],
                'tx_hash'   => $result['txid'] ?? null,
            ]);

            return redirect()
                ->route('dashboard')
                ->with('success', 'TRX sent! TXID: ' . ($result['txid'] ?? '—'));
        }

        // 5) On error, show detailed message
        $error = $result['error'] 
            ?? ($result['detail']['message'] ?? 'Unknown error');

        return back()
            ->withInput()
            ->withErrors(['send_error' => $error]);
    }

    public function transactionHistory()
    {
        $wallets = Auth::user()->wallets()->with('transactions')->get();
        return view('transactions.index', compact('wallets'));
    }
}
