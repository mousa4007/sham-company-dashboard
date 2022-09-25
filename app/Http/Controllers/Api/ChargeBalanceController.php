<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChargeBalanceController extends Controller
{
    public function chargeBalance(Request $request)
    {

        if($request->user()->balance >= 10){
            dd($request->user()->balance);
        }

        // if ($this->app_user_id) {

        //     $appUser = AppUser::find($this->app_user_id);

        //     $this->super_user = Agent::find($appUser->agent_id)->user;

        //     $this->max_balance = $this->super_user->balance;
        // }

        // $this->validate([
        //     'app_user_id' => 'required',
        //     'outgoingBalance' => "required|numeric|max:$this->max_balance",
        // ]);

        // $appUser->update([
        //     'balance' => $appUser->balance + $this->outgoingBalance,
        //     'outgoingBalance' => $appUser->outgoingBalance + $this->outgoingBalance,
        // ]);

        // $this->super_user->update([
        //     'balance' => $this->super_user->balance - $this->outgoingBalance,
        // ]);

        // $agent = Agent::find($appUser->agent_id);

        // $agent->update([
        //     'balance' => $agent->balance + $this->outgoingBalance
        // ]);

        // Notification::create([
        //     'message' => $this->charge_message,
        //     'app_user_id' => $this->app_user_id
        // ]);

        // AgentChargingBalance::create([
        //     'app_user_id' => $this->app_user_id,
        //     'name' => $appUser->name,
        //     'message' => $this->charge_message,
        //     'balance' => $this->outgoingBalance,
        //     'type' => 'charge'
        // ]);

        // $this->dispatchBrowserEvent('hide-create-modal', ['message' => 'تم شحن الرصيد بنجاح']);
    }
}
