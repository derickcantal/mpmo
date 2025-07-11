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
        $wallets = Auth::user()->wallets()->get();
        foreach ($wallets as $wallet) {
            $wallet->balance = $this->tron->getBalance($wallet->cwaddress);
        }
        return view('wallets.index', compact('wallets'));
    }

    public function createWallet()
    {
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

        dd($filename);

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
        return view('wallets.send', compact('address'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'address' => 'required',
            'to_address' => 'required',
            'amount' => 'required|numeric|min:0.000001',
        ]);

        $wallet = Auth::user()->wallets()->where('cwaddress', $request->address)->firstOrFail();
        $privateKey = decrypt($wallet->private_key);

        $result = $this->tron->sendTrx($privateKey, $request->to_address, $request->amount);

        if ($result['success'] ?? false) {
            Transaction::create([
                'cwid' => $wallet->cwid,
                'to_address' => $request->to_address,
                'amount' => $request->amount,
                'tx_hash' => $result['tx_hash'] ?? null,
            ]);
            return redirect()->route('dashboard')->with('success', 'TRX sent!');
        } else {
            return back()->withErrors(['send_error' => $result['message'] ?? 'Error sending TRX']);
        }
    }

    public function transactionHistory()
    {
        $wallets = Auth::user()->wallets()->with('transactions')->get();
        return view('transactions.index', compact('wallets'));
    }
}
