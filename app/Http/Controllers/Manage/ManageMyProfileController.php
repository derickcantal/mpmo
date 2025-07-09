<?php

namespace App\Http\Controllers\manage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use \Carbon\Carbon; 
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;
use Illuminate\Support\Facades\Storage;

class ManageMyProfileController extends Controller
{
    public function savemyavatar(Request $request,$userid)
    {
        $validated = $request->validate([
            'myavatar'=>'required|image|file',
        ]);
        // dd($request,$userid);

        $ipath = 'avatar/';

        if(!Storage::disk('public')->exists($ipath)){
            Storage::disk('public')->makeDirectory($ipath);
            // dd('path created');
        }
        $manager = ImageManager::imagick();
        $name_gen = hexdec(uniqid()).'.'.$request->file('myavatar')->getClientOriginalExtension();
        
        $image = $manager->read($request->file('myavatar'));
       
        $encoded = $image->toWebp()->save(storage_path('app/public/avatar/'.$name_gen.'.webp'));
        $path = 'avatar/'.$name_gen.'.webp';

        // $path = Storage::disk('public')->put('avatars',$request->file('avatar'));

        // $path = $request->file('avatar')->store('avatars','public');
        
        if($oldavatar = $request->user()->avatar){
            Storage::disk('public')->delete($oldavatar);
        }
        
        auth()->user()->update(['avatar' => $path]);

        return redirect()->back()
                                ->with('success','Avatar updated');
    }
    public function myavatar()
    {
        $user = User::where('userid',auth()->user()->userid)->first();
        
        return view('manage.myprofile.avatar')
                ->with(['user' => $user]);
    }

    public function savesignature(Request $request,$userid)
    {
        $validated = $request->validate([
            'owneraddress'=>'required|string|starts_with:T',
            'qrowneraddress'=>'required|image|file',
        ]);
        // dd($request,$userid);

        $ipath = 'userqr/';

        if(!Storage::disk('public')->exists($ipath)){
            Storage::disk('public')->makeDirectory($ipath);
            // dd('path created');
        }
        $manager = ImageManager::imagick();
        $name_gen = hexdec(uniqid()).'.'.$request->file('qrowneraddress')->getClientOriginalExtension();
        
        $image = $manager->read($request->file('qrowneraddress'));
       
        $encoded = $image->toWebp()->save(storage_path('app/public/userqr/with_'.$name_gen.'.webp'));
        $path = 'userqr/with_'.$name_gen.'.webp';

        // $path = Storage::disk('public')->put('avatars',$request->file('avatar'));

        // $path = $request->file('avatar')->store('avatars','public');
        
        if($oldavatar = $request->user()->ownerqrcwaddress){
            Storage::disk('public')->delete($oldavatar);
        }
        
        auth()->user()->update(['ownerqrcwaddress' => $path]);
        auth()->user()->update(['ownercwaddress' => $request->owneraddress]);

        return redirect()->back()
                                ->with('success','Withdrawal Address Updated');
    }
    public function changepassword()
    {
        $user = User::where('userid',auth()->user()->userid)->first();
        
        return view('manage.myprofile.changepassword')
                ->with(['user' => $user]);
    }

    public function signature()
    {
        $user = User::where('userid',auth()->user()->userid)->first();
        
        return view('manage.myprofile.signature')
                ->with(['user' => $user]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::where('userid',auth()->user()->userid)->first();
        
        return view('manage.myprofile.index')
                ->with(['user' => $user]);
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
    public function update(Request $request, $userid)
    {
        $userdetails = User::where('userid',auth()->user()->userid)->first();

        $mod = 0;
        $mod = $userdetails->mod;

        if(!empty($userdetails->middlename)){
            $middlename = $userdetails->middlename;
        }else{
            $middlename = $request->middlename;
        }
        

        $user = User::where('userid',auth()->user()->userid)->update([
            'middlename' => $middlename,
            'mobile_primary' => $request->mobile_primary,
            'mobile_secondary' => $request->mobile_secondary,
            'homeno' => $request->homeno,
            'updated_by' => auth()->user()->email,
            'mod' => $mod + 1,
        ]);

        if($user){
            
            return redirect()->back()
                        ->with('success','Updated successfully');
        }else{

            return redirect()->back()
                        ->with('failed','Update failed');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
