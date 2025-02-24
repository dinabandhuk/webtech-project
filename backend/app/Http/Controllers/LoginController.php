<?php

namespace App\Http\Controllers;

use App\Notifications\LoginNeedsVerification;
use Illuminate\Http\Request;
use App\Models\User;

class LoginController extends Controller
{
    public function submit(Request $request)
    {
        //validate phone number
        $request->validate([
            'phone' => 'required|numeric|min:10',
        ]);


        //find or create a user model
        $user = User::firstOrCreate([
            'phone'=> $request->phone
        ]);

        if (!$user)
        {
            return response()->json(['message' => 'could not process user with given phone number'], 401);
        }

        // send one time code
        $user->notify(new LoginNeedsVerification());


        // return a response
        return response()->json(['message'=> 'OTP code sent to phone number']);
    }

    public function verify(Request $request)
    {
        //validate incoming request
        $request->validate([
            'phone'=> 'required|numeric|min:10',
            'login_code'=>'required|numeric|between:11111,99999'
        ]);

        //find the user
        $user = User::where('phone', $request->phone)
        ->where('login_code', $request->login_code)
        ->first();

        // if user provided otp code is valid return an auth token
        if($user)
        {
            $user->update([
                'login_code'=>null
            ]);
            return $user->createToken($request->login_code)->plainTextToken;
        }

        // if invalid otp return back a message
        return response()->json(['message'=> 'Invalid verification code provided'],401);
    }
}
