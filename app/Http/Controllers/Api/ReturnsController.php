<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Returns;
use Illuminate\Http\Request;

class ReturnsController extends Controller
{
    public function createReturns(Request $request)
    {
        $request->validate([
            'return' => 'required',
            'reason' => 'required',
            'product_id' => 'required',
            'order_id' => 'required',
        ]);

        $return = Returns::create([
            'return' => $request->return,
            'reason' => $request->reason,
            'product_id' => $request->product_id,
            'app_user_id' => $request->user()->id,
            'order_id' => $request->order_id,
            'agent_id' => $request->user()->hasRole('agent') ? $request->user()->agent_id : '',
        ]);

        return response()->json([
            'return' => $return
        ]);
    }
}
