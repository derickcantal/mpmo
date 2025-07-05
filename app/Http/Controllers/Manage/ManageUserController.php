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

class ManageUserController extends Controller
{
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

    public function search(Request $request)
    {
        $user = User::whereNot('accessname','Requester')
                ->where(function(Builder $builder) use($request){
                    $builder->where('username','like',"%{$request->search}%")
                            ->orWhere('firstname','like',"%{$request->search}%")
                            ->orWhere('lastname','like',"%{$request->search}%")
                            ->orWhere('middlename','like',"%{$request->search}%")
                            ->orWhere('email','like',"%{$request->search}%")
                            ->orWhere('status','like',"%{$request->search}%"); 
                })
                ->orderBy('lastname',$request->orderrow)
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

         $user = User::whereNot('accesstype',"Renters")
                    ->orderBy('status','asc')
                    ->paginate(5);

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

        if(empty($user->rfid))
        {
            $rfid = $this->generateUniqueCode();
        }

        $fullname = $user->lastname . ', ' . $user->firstname . ' ' . $user->middlename;

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
                'rfid' => $rfid,
                'username' => $request->email,
                'email' => $request->email,
                'firstname' => $request->firstname,
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
    public function destroy($userid)
    {
        $user = User::where('userid', $userid)->first();
        $fullname = $user->lastname . ', ' . $user->firstname . ' ' . $user->middlename;
        // dd($userid,$fullname,$user);
        if($user->userid == auth()->user()->userid){
            $notes = 'Users. Activation. Self Account. ' . $fullname;

                return redirect()->route('manageuser.index')
                        ->with('failed','User Update on own account not allowed.');
        }
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');
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
}
