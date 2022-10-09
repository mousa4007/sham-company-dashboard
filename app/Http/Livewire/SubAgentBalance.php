<?php

namespace App\Http\Livewire;

use App\Models\Agent;
use App\Models\AgentChargingBalance;
use App\Models\AppUser;
use App\Models\Notification;
use App\Models\SuperUserChargingBalance;
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
        $this->charge_message = 'تم شحن حسابك ';
        $this->witdhraw_message = 'تم سحب من حسابك ';
        // $this->app_user_id = AppUser::first()->id == '' ? app_user_id = AppUser::first()->id : '';
        $this->paginateNumber = 10;
    }

    public function updatedIncomingBalance()
    {
        $this->charge_message =  'تم شحن حسابك ' . $this->incomingBalance . '$';
    }

    public function updatedOutgoingBalance()
    {
        $this->witdhraw_message =  'تم سحب من حسابك ' . $this->outgoingBalance . '$';
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
        $this->charge_message = 'تم شحن حسابك ';
        $this->witdhraw_message = 'تم سحب من حسابك ';
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
            'incomingBalance' => "required|numeric|max:$this->max_balance",
        ]);

        $appUser->update([
            'balance' => $appUser->balance + $this->incomingBalance,
            'incomingBalance' => $appUser->incomingBalance + $this->incomingBalance,
        ]);

        $appUser->notificationsCount->update([
            'notifications_count' =>  $appUser->notificationsCount->notifications_count + 1
        ]);

        $this->super_user->update([
            'balance' => $this->super_user->balance - $this->incomingBalance,
            'outgoingBalance' => $this->super_user->outgoingBalance + $this->incomingBalance,
        ]);

        $agent = Agent::find($appUser->agent_id);

        $agent->update([
            'balance' => $agent->balance + $this->incomingBalance
        ]);

        Notification::create([
            'title' => 'شحن رصيد',
            'message' => $this->charge_message,
            'app_user_id' => $this->app_user_id
        ]);


        AgentChargingBalance::create([
            'app_user_id' => $this->app_user_id,
            'name' => $appUser->name,
            'message' => $this->charge_message,
            'balance' => $this->incomingBalance,
            'type' => 'charge'
        ]);

        SuperUserChargingBalance::create([
            'app_user_id' => $this->app_user_id,
            'name' => $appUser->name,
            'message' => 'تم شحن حساب ' . $appUser->name . ' ' . $this->incomingBalance . '$',
            'balance' => $this->incomingBalance,
            'type' => 'withdraw'
        ]);

        $this->super_user->notificationsCount->update([
            'notifications_count' =>  $this->super_user->notifications_count + 1
        ]);

        $this->dispatchBrowserEvent('hide-create-modal', ['message' => 'تم شحن الرصيد بنجاح']);
    }

    public function withdrawBalance()
    {
        $appUser = AppUser::find($this->app_user_id);
        $super_user = Agent::find($appUser->agent_id)->user;

        $this->validate([
            'app_user_id' => 'required',
            'outgoingBalance' => "required|numeric|max:$appUser->balance",
        ]);

        $super_user->update([
            'balance' => $super_user->balance + $this->outgoingBalance,
            'incomingBalance' => $super_user->incomingBalance + $this->outgoingBalance,
        ]);

        $appUser->update([
            'balance' => $appUser->balance - $this->outgoingBalance,
            'outgoingBalance' => $appUser->outgoingBalance + $this->outgoingBalance,
        ]);

        $super_user->notificationsCount->update([
            'notifications_count' =>  $this->super_user->notifications_count + 1
        ]);

        $appUser->notificationsCount->update([
            'notifications_count' =>  $appUser->notificationsCount->notifications_count + 1
        ]);

        $agent = Agent::find($appUser->agent_id);

        $agent->update([
            'balance' => $agent->balance - $this->outgoingBalance
        ]);

        Notification::create([
            'title' => 'سحب رصيد',
            'message' => $this->witdhraw_message,
            'app_user_id' => $this->app_user_id
        ]);

        AgentChargingBalance::create([
            'app_user_id' => $this->app_user_id,
            'name' => $appUser->name,
            'message' => 'تم سحب مبلغ  ' . $this->outgoingBalance . '$',
            'balance' => $this->outgoingBalance,
            'type' => 'withdraw'
        ]);

        SuperUserChargingBalance::create([
            'app_user_id' => $this->app_user_id,
            'name' => $appUser->name,
            'message' => 'تم سحب من حساب '  . $appUser->name . ' ' . $this->outgoingBalance . '$',
            'balance' => $this->outgoingBalance,
            'type' => 'charge'
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

                if ($appUser->balance >= $balance) {

                    $super_user->update([
                        'balance' => $super_user->balance + $balance
                    ]);

                    $appUser->update([
                        'balance' => $appUser->balance - $balance,
                        'incomingBalance' => $appUser->incomingBalance - $balance,
                    ]);

                    $agent->update([
                        'balance' => $agent->balance - $balance,
                    ]);

                    $q->delete();

                    $this->reset('checked');

                    $this->dispatchBrowserEvent('hide-create-modal', ['message' => 'تم الإلغاء بنجاح']);
                } else {

                    $this->dispatchBrowserEvent('hide-delete-modal', ['message' => 'رصيد المستخدم غير كافي']);
                }
            } else {
                $super_user->update([
                    'balance' => $super_user->balance - $balance
                ]);

                $appUser->update([
                    'balance' => $appUser->balance + $balance,
                    'outgoingBalance' => $appUser->outgoingBalance - $balance,
                ]);

                $agent->update([
                    'balance' => $agent->balance + $balance
                ]);
            }

            $q->delete();

            $this->reset('checked');

            $this->dispatchBrowserEvent('hide-create-modal', ['message' => 'تم الإلغاء بنجاح']);
        });
    }
}
