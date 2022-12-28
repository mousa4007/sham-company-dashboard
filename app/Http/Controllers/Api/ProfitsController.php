<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Profit;
use App\Models\SuperUserChargingBalance;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProfitsController extends Controller
{
    //Super User Profits start
    public function superUserProfitsInDay(Request $request)
    {
        return $request->user()->profits()
            ->where('created_at', '>=', Carbon::now()->subDay())->where('agent_id', null)->sum('profit');
    }

    public function superUserProfitsInWeek(Request $request)
    {
        return $request->user()->profits()
            ->where('created_at', '>=', Carbon::now()->subWeek())->sum('profit');
    }

    public function superUserProfitsInMonth(Request $request)
    {
        return $request->user()->profits()
            ->where('created_at', '>=', Carbon::now()->subMonth())->sum('profit');
    }
    //Super User Profits end

    // Agents Profits start
    public function profitsFromAgentInDay(Request $request)
    {
        $request->validate([
            'agent_id' => 'required|integer'
        ]);

        return Profit::where('agent_id', $request->agent_id)
            ->where('created_at', '>=', Carbon::now()->subDay())->sum('profit');
    }

    public function profitsFromAgentInWeek(Request $request)
    {
        $request->validate([
            'agent_id' => 'required|integer'
        ]);

        return Profit::where('agent_id', $request->agent_id)
            ->where('created_at', '>=', Carbon::now()->subWeek())->sum('profit');
    }

    public function profitsFromAgentInMonth(Request $request)
    {
        $request->validate([
            'agent_id' => 'required|integer'
        ]);

        return Profit::where('agent_id', $request->agent_id)
            ->where('created_at', '>=', Carbon::now()->subMonth())->sum('profit');
    }
    // Agents Profits start


    public function withdrawProfits(Request $request)
    {
        $request->validate([
            'profit' => 'required | numeric'
        ]);
        $available_profit =  $request->user()->total_profits - $request->user()->whithdrawn_profits;
        // dd($available_profit < 10 );

        if ($request->profit == 0) {
            return 'zero';
        }

        if ($available_profit == 0) {
            return 'total_profits_0';
        }
        if ($request->profit < 10 || $available_profit < 10) {
            return 'less_than_10';
        }
        if ($request->profit > $available_profit) {
            return 'greater_than_available_profits';
        }
        if ($request->profit > $available_profit - 1 ) {
            return 'there_must_be_one_dollar_difference';
        }
        if ($request->profit <= $available_profit - 1) {
            $request->user()->update([
                'whithdrawn_profits' => $request->user()->whithdrawn_profits + $request->profit,
                'balance' =>   $request->user()->balance + $request->profit,
                'incomingBalance' =>   $request->user()->incomingBalance + $request->profit,
            ]);


            Notification::create([
                'title' => 'سحب مربح',
                'message' => 'تم سحب مربح من التطبيق' . $request->profit . '$',
                'app_user_id' =>  $request->user()->id
            ]);

            $request->user()->notificationsCount->update([
                'notifications_count' =>  $request->user()->notifications_count + 1
            ]);

            SuperUserChargingBalance::create([
                'app_user_id' => $request->user()->id,
                'name' => $request->user()->name,
                'message' => 'تم سحب مربح من التطبيق' . $request->profit . '$',
                'balance' => $request->profit,
                'type' => 'charge'
            ]);

            return 'success';
        }
    }
}
