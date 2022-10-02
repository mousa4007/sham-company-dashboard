<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ad;

class AdController extends Controller
{
    public function ads()
    {
        return Ad::latest()->pluck('image_url');
    }
}
