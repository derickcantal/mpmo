<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\cwallet;
use App\Models\transactions;
use App\Services\TronGridService;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected $tron;

    public function __construct(TronGridService $tron)
    {
        $this->tron = $tron;
    }

    public function index()
    {
        $wallets = Auth::user()->wallets();
        foreach ($wallets as $wallet) {
            $wallet->balance = $this->tron->getBalance($wallet->cwaddress);
        }
        return view('wallets.index', compact('wallets'));
    }

    public function createWallet()
    {
        $walletData = $this->tron->createWallet();
        dd($walletData);
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
