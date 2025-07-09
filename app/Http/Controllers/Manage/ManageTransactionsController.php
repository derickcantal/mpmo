<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\transactions;
use \Carbon\Carbon;
use League\CommonMark\Extension\Embed\Bridge\OscaroteroEmbedAdapter;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;

class ManageTransactionsController extends Controller
{
    public function storewithdraw(Request $request)
    {
        return redirect()->route('managetxn.index')
                        ->with('failed','Feature still on progress.');
    }

    public function withdraw(Request $request)
    {
        if(empty(auth()->user()->cwaddress))
        {
            return redirect()->route('managetxn.index')
                        ->with('failed','Please Upload Your Personal Wallet Address for withdrawal');
        }
        else
        {
            return view('manage.transactions.withdraw');
        }
    }

    public function storedeposit(Request $request)
    {
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');

        $validated = $request->validate([
            'txnhash'=>'required|string',
            'tokenname'=>'required|string',
            'amount'=>'required',
            'walletsource'=>'required|string',
            'imgproof'=>'required|image|file',
        ]);
        // dd($request);

        if(empty(auth()->user()->cwaddress))
        {
            return redirect()->route('managetxn.index')
                        ->with('failed','Unique Wallet Not Assigned');
        }

        $ipath = 'userdep/';

        if(!Storage::disk('public')->exists($ipath)){
            Storage::disk('public')->makeDirectory($ipath);
            // dd('path created');
        }
        $manager = ImageManager::imagick();
        $name_gen = hexdec(uniqid()).'.'.$request->file('imgproof')->getClientOriginalExtension();
        
        $image = $manager->read($request->file('imgproof'));
       
        $encoded = $image->toWebp()->save(storage_path('app/public/userdep/dep_'.$name_gen.'.webp'));
        $path = 'userdep/dep_'.$name_gen.'.webp';

        $transaction = transactions::create([
            'tokenid' => 0,
            'tokenname' => 'TRX',
            'txnhash' => $request->txnhash,
            'txnimg' => $path,
            'txntype' => 'DEPOSIT',
            'addresssend' => $request->walletsource,
            'addressreceive' => auth()->user()->cwaddress,
            'amount' => $request->amount,
            'amountvalue' => 0,
            'amountfee' => 0,
            'fullname' => auth()->user()->lastname.', '.auth()->user()->firstname,
            'created_by' => auth()->user()->email,
            'updated_by' => 'Null',
            'timerecorded' => $timenow,
            'mod' => 0,
            'status' => 'For Processing',
        ]);

        if($transaction)
        {
            return redirect()->route('managetxn.index')
                        ->with('success','Deposit Successful. You will receive an email once the transaction is finalized.');
        }else
        {
            return redirect()->route('managetxn.index')
                        ->with('failed','Transaction Failed');
        }

        
    }

    public function deposit(Request $request)
    {
        if(empty(auth()->user()->cwaddress))
        {
            return redirect()->route('managetxn.index')
                        ->with('failed','Unique Wallet Not Assigned');
        }
        else
        {
            return view('manage.transactions.deposit');
        }
    }

    public function storeconvert(Request $request)
    {
        return redirect()->route('managetxn.index')
                        ->with('failed','Feature still on progress.');
    }

    public function convert(Request $request)
    {
        if(empty(auth()->user()->cwaddress))
        {
            return redirect()->route('managetxn.index')
                        ->with('failed','Unique Wallet Not Assigned');
        }
        else
        {
            return view('manage.transactions.convert');
        }
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
