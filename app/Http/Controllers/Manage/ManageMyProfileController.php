<?php

namespace App\Http\Controllers\Manage;

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

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\WebPWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\QrCodeInterface;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\Label\Label;

class ManageMyProfileController extends Controller
{
    public function savemyavatar(Request $request,$userid)
    {
        $validated = $request->validate([
            'myavatar'=>'required|image|file',
        ]);
        // dd($request,$userid);

        $user = User::where('userid',$userid)->first();


        $ipath = 'avatar/';

        if(!Storage::disk('public')->exists($ipath)){
            Storage::disk('public')->makeDirectory($ipath);
            // dd('path created');
        }

        $manager = ImageManager::imagick();

        $uploadedFile = $request->file('myavatar');
        $image = $manager->read($uploadedFile->getRealPath());

        $webpData = (string) $image->toWebp(80);

        $path = 'avatar/u_' . hexdec(uniqid()) . '.webp';
        
        Storage::disk('public')->put($path, $webpData);
        if($request->user()->avatar != 'avatars/avatar-default.jpg')
        {
            if($oldavatar = $request->user()->avatar){
                Storage::disk('public')->delete($oldavatar);
            }
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
           // 'qrowneraddress'=>'required|image|file',
        ]);

        $ipath = 'userqr/';

        if(!Storage::disk('public')->exists($ipath)){
            Storage::disk('public')->makeDirectory($ipath);
            // dd('path created');
        }

        $data = $request->owneraddress;

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

        $path = 'userqr/with_' . hexdec(uniqid()) . '.webp';
        Storage::disk('public')->put($path, $finalWebp);

        if($oldavatar = $request->user()->ownerqrcwaddress){
            Storage::disk('public')->delete($oldavatar);
        }
        
        $user = auth()->user();

        // 1) Update an existing wallet
        $user->wallets()->update([
            'ownerqrcwaddress' => $path,
            'ownercwaddress'   => $request->owneraddress,
        ]);

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
        $user = User::where('userid',auth()->user()->userid)
                        ->with('wallets')
                        ->first();
        
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

        if(!empty($userdetails->birthdate)){
            $birthdate = $userdetails->birthdate;
        }else{
            $birthdate = $request->birthdate;
        }
        

        $user = User::where('userid',auth()->user()->userid)->update([
            'birthdate' => $birthdate,
            'mobile_primary' => $request->mobile_primary,
            'mobile_secondary' => $request->mobile_secondary,
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
