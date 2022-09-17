<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppUser;
use App\Models\Notification;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\Cast\String_;

class NotificationController extends Controller
{
    public function notifications()
    {
        return Notification::latest()->get();
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
