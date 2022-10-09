<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    public function changePassword(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password'=>'required'
        ]);

        if($request->email == $request->user()->email){
            $request->user()->update([
                'password' => Hash::make($request->password)
            ]);

            return 'success';
        }else{
            return 'email_not_match';
        }
    }
}
