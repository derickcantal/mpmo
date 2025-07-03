<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \Carbon\Carbon;
use App\Models\cwallet;
use Illuminate\Contracts\Database\Eloquent\Builder;

class ManageCWalletController extends Controller
{
    public function search(Request $request)
    {
        $wallet = cwallet::orderBy('cwid',$request->orderrow)
                ->where(function(Builder $builder) use($request){
                    $builder->where('cwaddress','like',"%{$request->search}%")
                            ->orWhere('wallcode','like',"%{$request->search}%")
                            ->orWhere('status','like',"%{$request->search}%"); 
                })
                ->orderBy('lastname',$request->orderrow)
                ->paginate($request->pagerow);
    
        return view('manage.wallets.index',compact('wallet'))
            ->with('i', (request()->input('page', 1) - 1) * $request->pagerow);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');

         $wallet = cwallet::orderBy('status','asc')
                    ->paginate(5);

        return view('manage.wallets.index',compact('wallet'))
         ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function create()
    {
       return view('manage.wallets.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request);
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');

        if(auth()->user()->accesstype == 'Supervisor')
        {
            if($request->accesstype == 'Adminstrator')
            {
                return redirect()->route('managewallet.index')
                        ->with('failed','Wallet creation failed');
            }
            elseif($request->accesstype == 'Supervisor')
            {

                return redirect()->route('managewallet.index')
                        ->with('failed','Wallet creation failed');
            }
        }
        $wallet = cwallet::create([
            'cwaddress'=> $request->address,
            'wallcode'=> $request->cc,
            'timerecorded'=> $timenow,
            'created_by'=> auth()->user()->userid,
            'mod'=> 0,
            'copied'=> 'N',
            'walletstatus'=> 'Inactive',
            'status'=> 'Inactive',
        ]);
    
        if ($wallet) {
    
            return redirect()->route('managewallet.index')
                        ->with('success','Wallet created successfully.');
        }else{

            return redirect()->route('managewallet.index')
                        ->with('failed','Wallet creation failed');
        }  
    }

    /**
     * Display the specified resource.
     */
    public function show($cwid)
    {
        $wallet = cwallet::where('cwid',$cwid)->first();

        return view('manage.wallets.show')
                    ->with(['wallet' => $wallet]);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($cwid)
    {
        $wallet = cwallet::where('cwid',$cwid)->first();

       return view('manage.wallets.edit')
                    ->with(['wallet' => $wallet]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $cwid)
    {
        $wallet = cwallet::where('cwid', $cwid)->first();

        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');

        $mod = 0;
        $mod = $wallet->mod;

        if(auth()->user()->accesstype == 'Supervisor')
        {
            if($request->accesstype == 'Administrator')
            {
                return redirect()->route('managewallet.index')
                        ->with('failed','Wallet update failed');
            }
            elseif($request->accesstype == 'Supervisor')
            {
       
                return redirect()->route('managewallet.index')
                        ->with('failed','Wallet update failed');
            }
        }
       
        $wallet =cwallet::where('cwid',$wallet->cwid)->update([
            'cwaddress'=> $request->address,
            'wallcode'=> $request->cc,
            'timerecorded'=> $timenow,
            'updated_by' => auth()->user()->userid,
            'mod'=> $mod + 1,
        ]);
        if($wallet){
            
            return redirect()->route('managewallet.index')
                        ->with('success','Wallet updated successfully');
        }else{

            return redirect()->route('managewallet.index')
                        ->with('failed','Wallet update failed');
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($cwid)
    {
        $wallet = cwallet::where('cwid', $cwid)->first();
       
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');
        if(auth()->user()->accesstype == 'Supervisor')
        {
            if($wallet->accesstype == 'Administrator')
            {

                return redirect()->route('managewallet.index')
                        ->with('failed','Wallet update failed');
            }
            elseif($wallet->accesstype == 'Supervisor')
            {

                return redirect()->route('managewallet.index')
                        ->with('failed','Wallet update failed');
            }
        }

        if($wallet->status == 'Active')
        {
            cwallet::where('cwid', $wallet->cwid)
            ->update([
            'status' => 'Inactive',
        ]);



        return redirect()->route('managewallet.index')
            ->with('success','Wallet Decativated successfully');
        }
        elseif($wallet->status == 'Inactive')
        {
            cwallet::where('cwid', $wallet->cwid)
            ->update([
            'status' => 'Active',
        ]);


        return redirect()->route('managewallet.index')
            ->with('success','Wallet Activated successfully');
        }
    }
}
