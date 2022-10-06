<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppUser;
use Illuminate\Http\Request;


class NotificationController extends Controller
{
    public function notifications(Request $request)
    {
        return $request->user()->notifications;
    }

    public function addAppUserFcmTokenKey(Request $request)
    {
        $data = $request->validate([
            'fcmToken' => 'required'
        ]);

        $id = $request->user()->id;

        AppUser::find($id)->query()->update([
            'fcmToken' => $data['fcmToken']
        ]);

        return 'Success';
    }
}
