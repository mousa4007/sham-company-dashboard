<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AgentChargingBalance;
use App\Models\SuperUserChargingBalance;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CashStatementController extends Controller
{
    public function cashStatementInDay(Request $request)
    {
        if ($request->user()->hasRole('super-user') || $request->user()->hasRole('user')) {
            return SuperUserChargingBalance::where('created_at', '>=', Carbon::now()->subDay())->latest()->get();
        } else {
            return AgentChargingBalance::where('created_at', '>=', Carbon::now()->subDay())->latest()->get();
        }
    }

    public function cashStatementInWeek(Request $request)
    {
        if ($request->user()->hasRole('super-user') || $request->user()->hasRole('user')) {
            return SuperUserChargingBalance::where('created_at', '>=', Carbon::now()->subWeek())->latest()->get();
        } else {
            return AgentChargingBalance::where('created_at', '>=', Carbon::now()->subWeek())->latest()->get();
        }
    }

    public function cashStatementInMonth(Request $request)
    {
        if ($request->user()->hasRole('super-user') || $request->user()->hasRole('user')) {
            return SuperUserChargingBalance::where('created_at', '>=', Carbon::now()->subMonth())->latest()->get();
        } else {
            return AgentChargingBalance::where('created_at', '>=', Carbon::now()->subMonth())->latest()->get();
        }
    }
}
