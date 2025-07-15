<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\temp_users;
use \Carbon\Carbon;
use League\CommonMark\Extension\Embed\Bridge\OscaroteroEmbedAdapter;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Database\Eloquent\Builder;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\WebPWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\QrCodeInterface;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\Label\Label;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;
use App\Services\TronGridService;
use App\Models\cwallet;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

use App\Http\Requests\UserSearchRequest;

class ManageUserController extends Controller
{
    protected $tron;

    public function __construct(TronGridService $tron)
    {
        $this->tron = $tron;
    }

    public function createwallet($user,$timenow,$fullname)
    {
        //dd('for wallet creation');
        if((!empty($user->cwaddress)))
        {
            return redirect()->route('manageuser.index')
                                ->with('failed','User has wallet');
        }
        
        if(empty($user->cwaddress))
        {
            // dd('No Wallet Assigned');
            $walletData = $this->tron->createWallet();

            $data = $walletData['address'];

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

            $filename = 'userqr/dep_' . hexdec(uniqid()) . '.webp';
            Storage::disk('public')->put($filename, $finalWebp);

             $wallet = cwallet::create([
                'userid' => $user->userid,
                'cwaddress' => $walletData['address'],
                'private_key' => encrypt($walletData['privateKey']),
                'public_key' => $walletData['publicKey'],
                'qrcwaddress'=> $filename,
                'timerecorded'=> $timenow,
                'created_by'=> auth()->user()->userid,
                'mod'=> 0,
                'copied'=> 'N',
                'walletstatus'=> 'Inactive',
                'status'=> 'Inactive',
            ]);

            if($wallet)
            {
                $wdata = cwallet::where('userid',$user->userid)->first();
                $user =User::where('userid',$user->userid)->update([
                    'cwid' => $wdata->cwid,
                    'cwaddress' => $walletData['address'],
                    'qrcwaddress'=> $filename,
                    'updated_by' => auth()->user()->email,
                    'walletstatus' => 'Active',
                ]);
                if($user){
                
                    return redirect()->route('manageuser.index')
                                ->with('success','Wallet Generated successfully');
                }else{

                    return redirect()->route('manageuser.index')
                                ->with('failed','Wallet Generation failed');
                }
            }
        }
       
    }

    public function generateUniqueCode()
    {

        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersNumber = strlen($characters);
        $codeLength = 6;

        $code = '';

        while (strlen($code) < 6) {
            $position = rand(0, $charactersNumber - 1);
            $character = $characters[$position];
            $code = $code.$character;
        }

        if (temp_users::where('rfid', $code)->exists()) {
            $this->generateUniqueCode();
        }

        return $code;

    }

    public function search(UserSearchRequest $request)
    {
        $search = $request->validated('search');

        $user = User::orderBy('fullname',$request->orderrow)
                ->where(function(Builder $builder) use($search){
                    $builder->where('username','like',"%{$search}%")
                            ->orWhere('fullname','like',"%{$search}%")
                            ->orWhere('email','like',"%{$search}%")
                            ->orWhere('status','like',"%{$search}%"); 
                })
                ->paginate($request->pagerow);
    
        return view('manage.users.index',compact('user'))
            ->with('i', (request()->input('page', 1) - 1) * $request->pagerow);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');

         $user = User::orderBy('status','asc')
                    ->with('wallets')
                    ->paginate(10);

        return view('manage.users.index',compact('user'))
         ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function create()
    {
       return view('manage.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request, $access, $department);
        $n1 = strtoupper($request->firstname[0]);
        // $n2 = strtoupper($request->middlename[0]);
        $n3 = strtoupper($request->lastname[0]);
        $n4 = preg_replace('/[-]+/', '', $request->birthdate);

        // $newpassword = $n1 . $n2 . $n3 . $n4;
        $newpassword = $n1 . $n3 . $n4;
        //dd($newpassword);

        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');

        if(auth()->user()->accesstype == 'Supervisor')
        {
            if($request->accesstype == 'Adminstrator')
            {
                return redirect()->route('manageuser.index')
                        ->with('failed','User creation failed');
            }
            elseif($request->accesstype == 'Supervisor')
            {

                return redirect()->route('manageuser.index')
                        ->with('failed','User creation failed');
            }
        }
        $user = User::create([
            'avatar' => 'avatars/avatar-default.jpg',
            'username' => $request->email,
            'email' => $request->email,
            'password' => Hash::make($newpassword),
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'birthdate' => $request->birthdate,
            'created_by' => auth()->user()->email,
            'updated_by' => 'Null',
            'timerecorded' => $timenow,
            'modifiedid' => 0,
            'mod' => 0,
            'status' => 'Active',
        ]);
    
        if ($user) {
    
            return redirect()->route('manageuser.index')
                        ->with('success','User created successfully.');
        }else{

            return redirect()->route('manageuser.index')
                        ->with('failed','User creation failed');
        }  
    }

    /**
     * Display the specified resource.
     */
    public function show($userid)
    {
        $user = User::where('userid',$userid)->first();

        return view('manage.users.show')
                    ->with(['user' => $user]);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($userid)
    {
        $user = User::where('userid',$userid)->first();

       return view('manage.users.edit')
                    ->with(['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $userid)
    {
        $user = User::where('userid', $userid)->first();

        if(empty($user->refid))
        {
            $refid = $this->generateUniqueCode();
        }
        else
        {
            $refid = $user->rfid;
        }


        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');

        $mod = 0;
        $mod = $user->mod;

        if(auth()->user()->accesstype == 'Supervisor')
        {
            if($request->accesstype == 'Administrator')
            {
                return redirect()->route('manageuser.index')
                        ->with('failed','User update failed');
            }
            elseif($request->accesstype == 'Supervisor')
            {
       
                return redirect()->route('manageuser.index')
                        ->with('failed','User update failed');
            }
        }

        if(!empty($request->password) != !empty($request->password_confirmation)){
            return redirect()->route('manageuser.index')
                    ->with('failed','User update failed');
        }
        if(empty($request->password)){
            $user =User::where('userid',$user->userid)->update([
                'refid' => $refid,
                'username' => $request->email,
                'email' => $request->email,
                'fullname' => $request->fullname,
                'birthdate' => $request->birthdate,
                'mobile_primary' => $request->mobile,
                'accesstype' => $request->accesstype,
                'rnotes' => $request->notes,
                'updated_by' => auth()->user()->email,
                'mod' => $mod + 1,
                'status' => $request->status,
            ]);
            if($user){
               
                return redirect()->route('manageuser.index')
                            ->with('success','User updated successfully');
            }else{

                return redirect()->route('manageuser.index')
                            ->with('failed','User update failed');
            }
        }elseif($request->password == $request->password_confirmation){
            $user =User::where('userid',$user->userid)->update([
                'username' => $request->email,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'firstname' => $request->firstname,
                'middlename' => $request->middlename,
                'lastname' => $request->lastname,
                'birthdate' => $request->birthdate,
                'mobile_primary' => $request->mobile,
                'accesstype' => $request->accesstype,
                'rnotes' => $request->notes,
                'updated_by' => auth()->user()->email,
                'mod' => $mod + 1,
                'status' => $request->status,
            ]);
            if($user){
                return redirect()->route('manageuser.index')
                            ->with('success','User updated successfully');
            }else{
             
                return redirect()->route('manageuser.index')
                            ->with('failed','User update failed');
            }
        }else{
            return redirect()->back()
                    ->with('failed','User update failed. Password Mismatched');
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function statuschange($user,$timenow,$fullname)
    {

        if($user->userid == auth()->user()->userid){
            $notes = 'Users. Activation. Self Account. ' . $fullname;

            return redirect()->route('manageuser.index')
                    ->with('failed','User Update on own account not allowed.');
        }

        if(auth()->user()->accesstype == 'Supervisor')
        {
            if($user->accesstype == 'Administrator')
            {

                return redirect()->route('manageuser.index')
                        ->with('failed','User update failed');
            }
            elseif($user->accesstype == 'Supervisor')
            {

                return redirect()->route('manageuser.index')
                        ->with('failed','User update failed');
            }
        }

        if($user->status == 'Active')
        {
            User::where('userid', $user->userid)
            ->update([
            'status' => 'Inactive',
        ]);



        return redirect()->route('manageuser.index')
            ->with('success','User Decativated successfully');
        }
        elseif($user->status == 'Inactive')
        {
            User::where('userid', $user->userid)
            ->update([
            'status' => 'Active',
        ]);


        return redirect()->route('manageuser.index')
            ->with('success','User Activated successfully');
        }
    }

    public function destroy(Request $request,$userid)
    {
        $user = User::where('userid', $userid)->first();
        $fullname = $user->lastname . ', ' . $user->firstname . ' ' . $user->middlename;
        // dd($userid,$fullname,$user);

        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');


        $action = $request->input('action');

        if ($action === 'save') {
            return $this->createwallet($user,$timenow,$fullname);
        } elseif ($action === 'delete') {
            return $this->statuschange($user,$timenow,$fullname);
        }
        
    }
}
