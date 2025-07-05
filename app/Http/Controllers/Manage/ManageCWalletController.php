<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \Carbon\Carbon;
use App\Models\cwallet;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;

class ManageCWalletController extends Controller
{
    public function userlistsearch (Request $request,$cwid)
    {
        $wallet = cwallet::where('cwid',$cwid)->first();

        $user = User::orderBy('lastname',$request->orderrow)
                ->where(function(Builder $builder) use($request){
                    $builder->where('username','like',"%{$request->search}%")
                            ->orWhere('firstname','like',"%{$request->search}%")
                            ->orWhere('lastname','like',"%{$request->search}%")
                            ->orWhere('middlename','like',"%{$request->search}%")
                            ->orWhere('email','like',"%{$request->search}%")
                            ->orWhere('status','like',"%{$request->search}%"); 
                })
                ->paginate($request->pagerow);
    
        return view('manage.wallets.userlist',compact('user'))
            ->with(compact('wallet'))
            ->with('i', (request()->input('page', 1) - 1) * $request->pagerow);
    }

    public function userliststore($cwid,$userid)
    {
        $wallet = cwallet::where('cwid',$cwid)->first();
        $user = User::where('userid',$userid)->first();

        // dd($wallet,$user);

        if(empty($user->cwid))
        {
            $users =User::where('userid',$user->userid)->update([
                'cwid' => $wallet->cwid,
                'cwaddress' => $wallet->cwaddress,
                'qrcwaddress' => $wallet->qrcwaddress,
            ]);

            $wallets =cwallet::where('cwid',$wallet->cwid)->update([
                'userid'=> $user->userid,
            ]);

            return redirect()->route('managewallet.index')
                        ->with('success','Wallet Successfully Assigned.');
        }else
        {
            return redirect()->route('managewallet.index')
                        ->with('failed','Wallet Already Assigned.');
        }

        
    }
    public function userlist($cwid)
    {
        $wallet = cwallet::where('cwid',$cwid)->first();

        $user = User::latest()->paginate(5);

        return view('manage.wallets.userlist',compact('user'))
         ->with(compact('wallet'))
         ->with('i', (request()->input('page', 1) - 1) * 5);

    }

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

        $validated = $request->validate([
            'walletqr'=>'required|image|file',
            'codeqr'=>'required|image|file',
        ]);

        $ipath = 'wallet/';

        if(!Storage::disk('public')->exists($ipath)){
            Storage::disk('public')->makeDirectory($ipath);
            // dd('path created');
        }

        $manager = ImageManager::imagick();
        $name_gen = hexdec(uniqid()).'.'.$request->file('walletqr')->getClientOriginalExtension();
        
        $image = $manager->read($request->file('walletqr'));
       
        $encoded = $image->toWebp()->save(storage_path('app/public/wallet/w_'.$name_gen.'.webp'));
        $walletqr = 'wallet/w_'.$name_gen.'.webp';

        $manager1 = ImageManager::imagick();
        $name_gen1 = hexdec(uniqid()).'.'.$request->file('codeqr')->getClientOriginalExtension();
        
        $image1 = $manager1->read($request->file('codeqr'));
       
        $encoded1 = $image1->toWebp()->save(storage_path('app/public/wallet/c_'.$name_gen1.'.webp'));
        $codeqr = 'wallet/c_'.$name_gen1.'.webp';

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
            'qrcwaddress'=> $walletqr,
            'wallcode'=> $request->cc,
            'qrwallcode'=> $codeqr,
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
        $oldqrcwaddress = $wallet->qrcwaddress;
        $oldqrwallcode = $wallet->qrwallcode;

        // dd($oldqrcwaddress,$oldqrwallcode);

        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');

        $mod = 0;
        $mod = $wallet->mod;

        $validated = $request->validate([
            'walletqr'=>'image|file',
            'codeqr'=>'image|file',
        ]);

        $ipath = 'wallet/';

        if(!Storage::disk('public')->exists($ipath)){
            Storage::disk('public')->makeDirectory($ipath);
            // dd('path created');
        }
        
        if(!empty($request->walletqr))
        {
            $result = Storage::disk('public')->delete($oldqrcwaddress);

            $manager = ImageManager::imagick();
            $name_gen = hexdec(uniqid()).'.'.$request->file('walletqr')->getClientOriginalExtension();
            
            $image = $manager->read($request->file('walletqr'));
        
            $encoded = $image->toWebp()->save(storage_path('app/public/wallet/w_'.$name_gen.'.webp'));
            $walletqr = 'wallet/w_'.$name_gen.'.webp';
            
        }
        else
        {
            $walletqr = $wallet->qrcwaddress;
        }

        if(!empty($request->codeqr))
        {
         $result = Storage::disk('public')->delete($oldqrwallcode);

        $manager1 = ImageManager::imagick();
        $name_gen1 = hexdec(uniqid()).'.'.$request->file('codeqr')->getClientOriginalExtension();
        
        $image1 = $manager1->read($request->file('codeqr'));
       
        $encoded1 = $image1->toWebp()->save(storage_path('app/public/wallet/c_'.$name_gen1.'.webp'));
        $codeqr = 'wallet/c_'.$name_gen1.'.webp';


        }
        else
        {
            $codeqr = $wallet->qrwallcode;
        }

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
            'qrcwaddress'=> $walletqr,
            'wallcode'=> $request->cc,
            'qrwallcode'=> $codeqr,
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
