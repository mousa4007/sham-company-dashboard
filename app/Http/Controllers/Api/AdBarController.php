<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AdBar;
use Illuminate\Http\Request;

class AdBarController extends Controller
{
    public function adBars()
    {
        $adbars = AdBar::where('status','active');

        return $adbars->pluck('adbar');
    }
}
