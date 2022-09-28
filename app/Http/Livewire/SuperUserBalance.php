<?php

namespace App\Http\Livewire;

use App\Models\AppUser;
use App\Models\Notification;
use App\Models\SuperUserChargingBalance;
use Livewire\Component;
use Livewire\WithPagination;

class SuperUserBalance extends Component
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
        $incomingBalance, $max_balance;

    public function render()
    {
        return view(
            'livewire.super_user_balance.super_user_balance',
            [
                'appUsers' => $this->app_users,
                'superUserChargingBalance' => $this->superUserChargingBalance
            ]
        );
    }

    public function mount()
    {
        $this->charge_message = 'تم شحن حسابك بمبلغ  ' . $this->balance;
        $this->witdhraw_message = 'تم سحب مبلغ ' . $this->current_balance . 'من رصيدك';
        // $this->app_user_id = AppUser::first()->id;
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
            $this->current_balance = AppUser::find($this->app_user_id)->balance;
        } else {
            $this->current_balance = '';
        }
    }

    public function updatedChecked($value)
    {
        if ($value) {
            $this->selectedRows = $this->superUserChargingBalance->pluck('id');
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
                ->whereRoleIs('super-user')
                ->orWhereRoleIs('user')
                ->latest()->paginate($this->paginateNumber);
        } else {
            return AppUser::query()
                ->where('status', 'active')
                ->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('email', 'like', '%' . $this->searchTerm . '%');
                })
                ->whereRoleIs('super-user')
                ->orWhereRoleIs('user')
                ->latest()->paginate($this->paginateNumber);
        }
    }

    public function getSuperUserChargingBalanceProperty()
    {
        if ($this->from) {
            return SuperUserChargingBalance::query()
                ->whereDate('created_at', '>=', $this->from)
                ->whereDate('created_at', '<=', $this->to)
                ->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('balance', 'like', '%' . $this->searchTerm . '%');
                })
                ->latest()->paginate($this->paginateNumber);
        } else {
            return SuperUserChargingBalance::query()
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
        $this->app_user_id = '';
        $this->current_balance = '';
    }

    public function chargeBalance()
    {
        $appUser = AppUser::find($this->app_user_id);

        $this->validate([
            'app_user_id' => 'required|numeric',
            'outgoingBalance' => "required|numeric|max:1000",
        ]);

        $appUser->update([
            'balance' => $appUser->balance + $this->outgoingBalance,
            'outgoingBalance' => $appUser->outgoingBalance + $this->outgoingBalance,
        ]);

        Notification::create([
            'message' => $this->charge_message,
            'app_user_id' => $this->app_user_id
        ]);

        SuperUserChargingBalance::create([
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
        if ($this->app_user_id) {

            $appUser = AppUser::find($this->app_user_id);

            $this->max_balance = $appUser->balance;
        }
        $this->validate([
            'app_user_id' => 'required|numeric',
            'incomingBalance' => "required|numeric|max:$this->max_balance",
        ]);


        $appUser->update([
            'balance' => $appUser->balance - $this->incomingBalance,
            'incomingBalance' => $appUser->incomingBalance + $this->incomingBalance,
        ]);

        Notification::create([
            'message' => $this->witdhraw_message,
            'app_user_id' => $this->app_user_id
        ]);

        SuperUserChargingBalance::create([
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
        // cancel process in charging to official agent balance :
        // update balance in :
        // 1 - appUser table
        // 2- super user in app user table
        // 3- update incoming and outgoing table

        SuperUserChargingBalance::whereIn('id', $this->selectedRows)->each(function ($q) {

            $balance = $q->balance;

            $appUser = AppUser::find($q->app_user_id);

            if ($q->type == 'charge') {
                $appUser->update([
                    'balance' => $appUser->balance - $balance,
                    'outgoingBalance' => $appUser->outgoingBalance - $balance,
                ]);
            } else {
                $appUser->update([
                    'balance' => $appUser->balance + $balance,
                    'incomingBalance' => $appUser->incomingBalance - $balance,
                ]);
            }

            SuperUserChargingBalance::create([
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
