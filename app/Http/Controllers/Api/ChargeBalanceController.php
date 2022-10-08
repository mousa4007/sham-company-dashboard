<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\AgentChargingBalance;
use App\Models\AppUser;
use App\Models\Notification;
use App\Models\SuperUserChargingBalance;
use Illuminate\Http\Request;

class ChargeBalanceController extends Controller
{
    public function chargeAgentBalance(Request $request)
    {
        $balance = $request->user()->balance;

        $request->validate([
            'balance' => 'required',
            'agent_id' => 'required'
        ]);

        $agent = AppUser::where('agent_id',$request->agent_id)->first();

        $agent_table = Agent::find($request->agent_id);

        if ($balance >= 10 && $balance >= $request->balance) {
            if ($request->balance >= 10) {

                $request->user()->update([
                    'balance' =>   $request->user()->balance - $request->balance,
                    'outgoingBalance' => $request->user()->outgoingBalance + $request->balance,
                ]);

                $request->user()->notificationsCount->update([
                    'notifications_count' =>   $request->user()->notificationsCount->notifications_count + 1
                ]);

                $agent->notificationsCount->update([
                    'notifications_count' =>  $agent->notificationsCount->notifications_count + 1
                ]);

                $agent->update([
                    'balance' =>  $agent->balance + $request->balance,
                    'incomingBalance' => $agent->incomingBalance + $request->balance,
                ]);

                $agent_table->update([
                    'balance' =>  $agent_table->balance + $request->balance,
                ]);

                Notification::create([
                    'title' => 'شحن رصيد',
                    'message' => 'تم شحن حسابك بمبلغ ' . $request->balance,
                    'app_user_id' => $request->agent_id
                ]);

                AgentChargingBalance::create([
                    'app_user_id' => $agent->id,
                    'name' => $agent->name,
                    'message' => 'تم شحن حسابك بمبلغ ' . $request->balance,
                    'balance' => $request->balance,
                    'type' => 'charge'
                ]);

                SuperUserChargingBalance::create([
                    'app_user_id' => $agent->id,
                    'name' => $agent->name,
                    'message' => 'تم سحب مبلغ من حسابك ' . $request->balance,
                    'balance' => $request->balance,
                    'type' => 'withdraw'
                ]);
                return 'success';
            } else {
                return 'must_be_greater_than_10';
            }
        } else {
            return 'balance_not_enough';
        }
    }
}
