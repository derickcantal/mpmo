<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\temp_users;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use \Carbon\Carbon; 
use App\Http\Requests\TempUserSearchRequest;

class ManageTempUsersController extends Controller
{
    public function search(TempUserSearchRequest $request)
    {
        $search = $request->validated('search');

        $user = temp_users::orderBy('fullname',$request->orderrow)
                ->where(function(Builder $builder) use($search){
                    $builder->where('username','like',"%{$search}%")
                            ->orWhere('email','like',"%{$search}%")
                            ->orWhere('status','like',"%{$search}%"); 
                })
                ->paginate($request->pagerow);
    
        return view('manage.tempusers.index',compact('user'))
            ->with('i', (request()->input('page', 1) - 1) * $request->pagerow);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = temp_users::orderBy('userid','asc') 
                    ->paginate(5);

        // $notes = 'Users';
        // $status = 'Success';
        // $this->userlog($notes,$status);

        return view('manage.tempusers.index',compact('user'))
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
    public function show($userid)
    {
        $user = temp_users::where('userid',$userid)->first();

        return view('manage.tempusers.show')
                    ->with(['user' => $user]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($userid)
    {
        $user = temp_users::where('userid',$userid)->first();

       return view('manage.tempusers.edit')
                    ->with(['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $userid)
    {
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');

        $user = temp_users::where('userid', $userid)->first();

        if($request->input('action') == "updateaccept") {
            // $this->show($userid);
            $user = temp_users::where('userid',$user->userid)->update([
                'username' => $request->email,
                'email' => $request->email,
                'fullname' => $request->fullname,
                'birthdate' => $request->birthdate,
                'mobile_primary' => $request->mobile,
                'notes' => $request->notes,
                'updated_by' => auth()->user()->email,
                'mod' => 0,
                'status' => 'Active',
            ]);

            $moveuser = temp_users::query()
                            ->where('userid', $userid)
                            ->each(function ($oldRecord) {
                                $newRecord = $oldRecord->replicate();
                                $newRecord->setTable('users');
                                $newRecord->save();
                                $oldRecord->delete();
                            });

            if($moveuser){
                return redirect()->route('managetempusers.index')
                            ->with('success','User Updated and Moved successfully');
            }
        }elseif($request->input('action') ==  "update") {

            $mod = 0;
            $mod = $user->mod;
            
            $user =temp_users::where('userid',$user->userid)->update([
                    'username' => $request->email,
                    'email' => $request->email,
                    'fullname' => $request->fullname,
                    'birthdate' => $request->birthdate,
                    'mobile_primary' => $request->mobile,
                    'notes' => $request->notes,
                    'updated_by' => auth()->user()->email,
                    'mod' => $mod + 1,
                ]);
                
                if($user){
                    return redirect()->route('managetempusers.index')
                                ->with('success','User updated successfully');
                }else{
                    
                    return redirect()->route('managetempusers.index')
                                ->with('failed','User update failed');
                }
  
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($userid)
    {
        //
    }
}
