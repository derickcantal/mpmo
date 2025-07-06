<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\transactions;
use \Carbon\Carbon;
use League\CommonMark\Extension\Embed\Bridge\OscaroteroEmbedAdapter;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Database\Eloquent\Builder;

class ManageTransactionsController extends Controller
{
    public function withdrawstore(Request $request)
    {
        
    }

    public function withdraw(Request $request)
    {
        
    }

    public function depositstore(Request $request)
    {
        
    }

    public function deposit(Request $request)
    {
        
    }

    public function convertstore(Request $request)
    {
        
    }

    public function convert(Request $request)
    {
        return view('manage.transactions.convert');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');
        
        $transaction = transactions::orderBy('status','asc')
                    ->paginate(5);

        return view('manage.transactions.index',compact('transaction'))
         ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
