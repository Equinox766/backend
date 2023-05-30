<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\ProfileUserResource;

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
            $path = Storage::putFile('users', $request->file("imagen"));
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
}
