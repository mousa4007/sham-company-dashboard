<?php

namespace App\Http\Livewire;

use App\Models\AppUser;
use App\Models\Discount;
use Livewire\Component;

class UsersDiscounts extends Component
{
    public $discount, $user_id;
    public AppUser $app_user;

    protected $rules = [
        'discount' => 'required',
        'user_id' => 'required',
    ];

    public function mount()
    {
        $this->app_user = new AppUser();
    }

    public function render()
    {
        return view('livewire.discounts.users_discount', ['app_users' => AppUser::all(), 'discounts' => Discount::all()]);
    }

    public function addUserDiscount()
    {
        $this->validate();

        if ($this->user_id == 'all') {
            AppUser::query()->update([
                'discount' => $this->discount
            ]);
        } else {
            $user = AppUser::find($this->user_id);
            $user->update([
                'discount' => $this->discount
            ]);
        }

        $this->dispatchBrowserEvent('hide-create-modal', ['message' => 'تمت الإضافة بنجاح']);
    }
}
