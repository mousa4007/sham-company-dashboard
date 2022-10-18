<?php

namespace App\Http\Livewire;

use App\Models\AppUser;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Home extends Component
{
    public $users, $users_count,$total_users,$orders,$orders_count,$sales_count;
    public function render()
    {
        return view('livewire.home');
    }

    public function mount()
    {
        $this->total_users = count(AppUser::all());
        $this->sales_count = count(Order::all());

        $this->users =  DB::table('app_users')
            ->whereDate('created_at', '>=', now()->subDays(60))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as users'))
            ->groupBy('date')
            ->pluck('date');


        $this->users_count =  DB::table('app_users')
            ->whereDate('created_at', '>=', now()->subDays(60))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as users'))
            ->groupBy('date')
            ->pluck('users');

            $this->orders =  DB::table('orders')
            ->whereDate('created_at', '>=', now()->subDays(60))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as orders'))
            ->groupBy('date')
            ->pluck('date');


        $this->orders_count =  DB::table('orders')
            ->whereDate('created_at', '>=', now()->subDays(60))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as orders'))
            ->groupBy('date')
            ->pluck('orders');
    }
}
