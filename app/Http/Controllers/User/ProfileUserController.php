<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\ProfileUserResource;
use Illuminate\Support\Str;

class ProfileUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function profileUpdate(Request $request){
        $user = auth('api')->user();

        $userModel = User::findOrFail($user->id);

        if($request->hasFile("imagen")){
            if($userModel->avatar){
                Storage::delete($userModel->avatar);
            }
            $extension = $request->file('imagen')->getClientOriginalExtension();
            $imageName = Str::slug('AVATAR') . '-' . uniqid() . '.' . $extension;
            $path = $request->file('imagen')->storeAs('users', $imageName);
            $request->request->add(["avatar" => $path]);
        }
        $userModel->update($request->all());

        return response()->json(
            [
                'message' => 200,
                'user' => ProfileUserResource::make($userModel)
            ]
        );
    }
    public function updatePassword(Request $request)
    {

//        #Match The Old Password
//        if(!Hash::check($request->old_password, auth()->user()->password)){
//            return response()->json("error", "Old Password Doesn't match!");
//        }

        #Update the new Password
        User::whereId(auth()->user()->id)->update([
            'password' => Hash::make($request->password)
        ]);

        return response()->json(
        [
            "status" => "Password changed successfully!"
        ], 200);
    }
    public function contactUsers()
    {
        $users = User::where('id', '<>', auth('api')->user()->id)->orderBy('id', 'desc')->get();
        return response()->json([
            "users" => ProfileUserResource::collection($users)
        ]);
    }
}
