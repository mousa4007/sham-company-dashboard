<?php

namespace App\Http\Livewire;

use App\Models\Agent;
use App\Models\AgentChargingBalance;
use App\Models\AppUser;
use App\Models\Notification;
use Livewire\Component;
use Livewire\WithPagination;

class SubAgentBalance extends Component
{
    use  WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $balance,
        $ids,
        $searchTerm,
        $checked = false,
        $selectedRows = [],
        $paginateNumber,
        $from,
        $to,
        $app_user_id,
        $charge_message,
        $witdhraw_message,
        $current_balance,
        $outgoingBalance,
        $incomingBalance,
        $official_agent_balance,
        $max_balance,
        $super_user;

    public function render()
    {
        return view(
            'livewire.sub_agent_balance.sub_agent_balance',
            [
                'appUsers' => $this->app_users,
                'agentChargingBalance' => $this->agentChargingBalance,
            ]
        );
    }

    public function mount()
    {
        $this->charge_message = 'تم شحن حسابك بمبلغ  ' . $this->balance;
        $this->witdhraw_message = 'تم سحب مبلغ من حسابك  ' . $this->balance;
        // $this->app_user_id = AppUser::first()->id == '' ? app_user_id = AppUser::first()->id : '';
        $this->paginateNumber = 10;
    }

    public function updatedOutgoingBalance()
    {
        $this->charge_message =  'تم شحن حسابك بمبلغ  ' . $this->outgoingBalance . '$';
    }

    public function updatedIncomingBalance()
    {
        $this->witdhraw_message =  'تم سحب مبلغ من حسابك  ' . $this->incomingBalance . '$';
    }


    public function updatedAppUserId()
    {
        if (AppUser::find($this->app_user_id)) {
            $appUser = AppUser::find($this->app_user_id);

            $this->official_agent_balance  = Agent::find($appUser->agent_id)->user->balance;
            $this->current_balance  = $appUser->balance;
        }
    }
    public function updatedChecked($value)
    {
        if ($value) {
            $this->selectedRows = $this->agentChargingBalance->pluck('id');
        } else {
            $this->reset(['checked', 'selectedRows']);
        }
    }

    public function getAppUsersProperty()
    {
        if ($this->from) {
            return AppUser::query()
                ->where('status', 'active')
                ->whereDate('created_at', '>=', $this->from)
                ->whereDate('created_at', '<=', $this->to)
                ->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('email', 'like', '%' . $this->searchTerm . '%');
                })
                ->whereRoleIs('agent')
                ->latest()->paginate($this->paginateNumber);
        } else {
            return AppUser::query()
                ->where('status', 'active')
                ->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('email', 'like', '%' . $this->searchTerm . '%');
                })
                ->whereRoleIs('agent')
                ->latest()->paginate($this->paginateNumber);
        }
    }

    public function getAgentChargingBalanceProperty()
    {
        if ($this->from) {
            return AgentChargingBalance::query()
                ->whereDate('created_at', '>=', $this->from)
                ->whereDate('created_at', '<=', $this->to)
                ->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('balance', 'like', '%' . $this->searchTerm . '%');
                })
                ->latest()->paginate($this->paginateNumber);
        } else {
            return AgentChargingBalance::query()
                ->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('balance', 'like', '%' . $this->searchTerm . '%');
                })
                ->latest()->paginate($this->paginateNumber);
        }
    }

    public function resetData()
    {
        $this->outgoingBalance = '';
        $this->incomingBalance = '';
        $this->charge_message = 'تم شحن حسابك بمبلغ ';
        $this->witdhraw_message = 'تم سحب مبلغ من حسابك ';
        $this->app_user_id = "";
        $this->current_balance = '';
    }

    public function chargeBalance()
    {

        if ($this->app_user_id) {

            $appUser = AppUser::find($this->app_user_id);

            $this->super_user = Agent::find($appUser->agent_id)->user;

            $this->max_balance = $this->super_user->balance;
        }

        $this->validate([
            'app_user_id' => 'required',
            'outgoingBalance' => "required|numeric|max:$this->max_balance",
        ]);

        $appUser->update([
            'balance' => $appUser->balance + $this->outgoingBalance,
            'outgoingBalance' => $appUser->outgoingBalance + $this->outgoingBalance,
        ]);

        $this->super_user->update([
            'balance' => $this->super_user->balance - $this->outgoingBalance,
        ]);

        $agent = Agent::find($appUser->agent_id);

        $agent->update([
            'balance' => $agent->balance + $this->outgoingBalance
        ]);

        Notification::create([
            'message' => $this->charge_message,
            'app_user_id' => $this->app_user_id
        ]);

        AgentChargingBalance::create([
            'app_user_id' => $this->app_user_id,
            'name' => $appUser->name,
            'message' => $this->charge_message,
            'balance' => $this->outgoingBalance,
            'type' => 'charge'
        ]);

        $this->dispatchBrowserEvent('hide-create-modal', ['message' => 'تم شحن الرصيد بنجاح']);
    }

    public function withdrawBalance()
    {
        $appUser = AppUser::find($this->app_user_id);
        $super_user = Agent::find($appUser->agent_id)->user;

        $this->validate([
            'app_user_id' => 'required',
            'incomingBalance' => "required|numeric|max:$appUser->balance",
        ]);

        $super_user->update([
            'balance' => $super_user->balance + $this->incomingBalance,
            'incomingBalance' => $super_user->incomingBalance - $this->incomingBalance,
        ]);

        $appUser->update([
            'balance' => $appUser->balance - $this->incomingBalance,
            'incomingBalance' => $appUser->incomingBalance + $this->incomingBalance,
        ]);

        $agent = Agent::find($appUser->agent_id);

        $agent->update([
            'balance' => $agent->balance - $this->incomingBalance
        ]);

        Notification::create([
            'message' => $this->witdhraw_message,
            'app_user_id' => $this->app_user_id
        ]);

        AgentChargingBalance::create([
            'app_user_id' => $this->app_user_id,
            'name' => $appUser->name,
            'message' => $this->witdhraw_message,
            'balance' => $this->incomingBalance,
            'type' => 'withdraw'
        ]);

        $this->dispatchBrowserEvent('hide-create-modal', ['message' => 'تم السحب من الرصيد بنجاح']);
    }

    public function cancelCharging()
    {
        // cancel process in chargin to agent balance :
        // update balance in :
        // 1 - agent table
        // 2 - appUser table
        // 3- super user in app user table
        // 4- update incoming and outgoing table

        //check the


        AgentChargingBalance::whereIn('id', $this->selectedRows)->each(function ($q) {

            $balance = $q->balance;

            $appUser = AppUser::find($q->app_user_id);

            $agent_id = $appUser->agent_id;

            $agent = Agent::find($agent_id);

            $super_user = $agent->user;


            if ($q->type == 'charge') {
                $super_user->update([
                    'balance' => $super_user->balance + $balance
                ]);

                $appUser->update([
                    'balance' => $agent->balance - $balance,
                    'outgoingBalance' => $appUser->outgoingBalance - $balance,
                ]);

                $agent->update([
                    'balance' => $agent->balance - $balance,
                ]);
            } else {
                $super_user->update([
                    'balance' => $super_user->balance - $balance
                ]);

                $appUser->update([
                    'balance' => $appUser->balance + $balance,
                    'incomingBalance' => $appUser->incomingBalance - $balance,
                ]);

                $agent->update([
                    'balance' => $agent->balance + $balance
                ]);
            }

            AgentChargingBalance::create([
                'app_user_id' => $appUser->id,
                'name' => $appUser->name,
                'message' => 'تم إلغاء شحن حسابك بمبلغ ' . $balance . '$',
                'balance' => $balance,
                'type' => 'cancel'
            ]);

            $q->delete();

            $this->reset('checked');
        });

        $this->dispatchBrowserEvent('hide-create-modal', ['message' => 'تم الإلغاء بنجاح']);
    }
}
