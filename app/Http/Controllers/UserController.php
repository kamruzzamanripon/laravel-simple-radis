<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function allUser()
    {
        
        if(! $allUserInfo = redisKeyExist('user', 1)){
            $allUserInfo = User::orderBy('created_at', 'desc')->get();
            redisSetData("user", $allUserInfo, 86400);
        }
        
        return view('welcome', ['allUserInfo' => $allUserInfo]);
    }

    public function addUser(Request $request)
    {
        //dd($request->all());
        $newUser = new User();

        $newUser->name = $request->name;
        $newUser->email = $request->email;
        $newUser->password = Hash::make($request->password);
        $newUser->save();

        redisResetData('user');
        
        return redirect()->route('allUser');
    }
}
