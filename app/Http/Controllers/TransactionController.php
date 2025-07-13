<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        $txs = auth()->user()->transactions()->latest()->paginate(10);
        return view('transaction.index', compact('txs'));
    }
}
