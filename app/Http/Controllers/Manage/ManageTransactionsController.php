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
use App\Models\TokenMetric;
use App\Services\TokenService;

use App\Http\Requests\TransactionSearchRequest;

class ManageTransactionsController extends Controller
{

    protected TokenService $tokenService;

    public function __construct(TokenService $tokenService)
    {
        $this->tokenService = $tokenService;
    }

    public function generateUniqueCode()
    {

        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $charactersNumber = strlen($characters);
        $codeLength = 64;

        $code = '';

        while (strlen($code) < $codeLength) {
            $position = rand(0, $charactersNumber - 1);
            $character = $characters[$position];
            $code = $code.$character;
        }

        if (transactions::where('txnhash', $code)->exists()) {
            $this->generateUniqueCode();
        }

        return $code;

    }

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
            'addressreceive' => auth()->user()->wallets[0]->cwaddress,
            'trx_amount' => $request->amount,
            'userid' => auth()->user()->userid,
            'cwid' => auth()->user()->cwid,
            'created_by' => auth()->user()->userid,
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
    public function storeconvert(Request $request, TokenService $svc)
    {
        // dd('rer');
        $user     = $request->user();
        $maxTrx   = $user->trx_balance;

        $data = $request->validate([
            'trx_amount' => [
                'required',
                'numeric',
                'gt:0',                       // must be > 0
                'max:'.$maxTrx,               // not more than they have
            ],
        ], [
            'trx_amount.max' => "You only have {$maxTrx} TRX available.",
        ]);

        $this->tokenService->convert($user, (float) $data['trx_amount']);
        $user        = auth()->user();
        $trxBalance  = $user->trx_balance;    // make sure this column exists on users
        $mpmoBalance = $user->mpmox_balance; 

        return redirect()
            ->route('managetxn.convert')
            ->with(['trxBalance','mpmoBalance'])
            ->with('success', 'TRX successfully converted to MPMO');

        $data = $request->validate(['trx_amount'=>'required|numeric|max:'.auth()->user()->trx_balance]);
        $svc->convert(auth()->user(), $data['trx_amount']);
        $user = auth()->user()->refresh();
        return response()->json(['message'=>'Converted!',
                                'trx_balance'=>$user->trx_balance,
                                'mpmo_balance'=>$user->mpmo_balance]);
    }
    public function storeconvert1(Request $request)
    {
        $wallets = auth()->user()->wallets()->get();
        // dd($this->generateUniqueCode());
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');

        $trxAmount = $request->input('trxAmount');

        $data = $request->validate([
            'trxAmount' => 'required|numeric|min:0.01|max:'.$wallets[0]->trxbal,
        ]);
        $trxAmount = $data['trxAmount'];

        $feeRate = 0.02;
        $conversionRate = 3;
        $grossMpmo = $trxAmount * $conversionRate;
        $fee     = $grossMpmo * $feeRate;
        $netMpmo = $grossMpmo - $fee;

        $user = $wallets[0];
        $user->trxbal  -= $trxAmount;
        $user->mpmobal += $netMpmo;
        $user->save();

        $txnhash = $this->generateUniqueCode();

        // dd($request);
        $transaction = transactions::create([
            'cwid' => auth()->user()->cwid,
            'tokenid' => 0,
            'tokenname' => 'TRX',
            'txnimg' => 'NULL',
            'txnhash' => $txnhash,
            'txntype' => 'CONVERT',
            'addresssend' => $wallets[0]->cwaddress,
            'addressreceive' => $wallets[0]->cwaddress,
            'amount' => $trxAmount,
            'amountvalue' => $netMpmo,
            'amountfee' => $fee,
            'userid' => $wallets[0]->userid,
            'timerecorded' => $timenow,
            'created_by' => auth()->user()->email,
            'mod' => 0,
            'status' => 'Success',
        ]);

        $transaction1 = transactions::create([
            'cwid' => auth()->user()->cwid,
            'tokenid' => 0,
            'tokenname' => 'MPMO',
            'txnimg' => 'NULL',
            'txnhash' => $txnhash,
            'txntype' => 'RECEIVE',
            'addresssend' => $wallets[0]->cwaddress,
            'addressreceive' => $wallets[0]->cwaddress,
            'amount' => $netMpmo,
            'amountvalue' => $trxAmount,
            'amountfee' => 0,
            'userid' => $wallets[0]->userid,
            'timerecorded' => $timenow,
            'created_by' => auth()->user()->email,
            'mod' => 0,
            'status' => 'Success',
        ]);


        return redirect()->route('managetxn.index')
                        ->with('success','Conversion Success.');
    }

    public function convert(Request $request)
    {
        $wallets = auth()->user()->wallets()->get();

        $user        = auth()->user();
        $trxBalance  = $user->trx_balance;    // make sure this column exists on users
        $mpmoBalance = $user->mpmo_balance;   // …and this one, too

        if(empty($wallets[0]->cwaddress))
        {
            return redirect()->route('managetxn.index')
                        ->with('failed','Unique Wallet Not Assigned');
        }
        else
        {
            return view('manage.transactions.convert',compact('wallets',   
                                                              'trxBalance',
                                                              'mpmoBalance'));
        }
    }

    public function search(TransactionSearchRequest $request)
    {
        $search = $request->validated('search');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');
        
        $transaction = transactions::query()
                    ->latest()
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
    public function show($txnid)
    {
        $transaction = transactions::where('txnid',$txnid)->first();

        return view('manage.transactions.show',compact('transaction'));
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
