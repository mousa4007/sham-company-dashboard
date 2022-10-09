<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PasswordController extends Controller
{
    public function changePassword(Request $request)
    {
        $request->validate([
            'previous_password' => 'required',
            'password'=>'required'
        ]);

        if(Hash::check($request->previous_password,$request->user()->password)){
            $request->user()->update([
                'password' => Hash::make($request->password)
            ]);

            return 'success';
        }else{
            return 'password_not_match';
        }
    }

}
