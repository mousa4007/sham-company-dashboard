<?php

namespace App\Http\Livewire;

use App\Models\AppUser;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Home extends Component
{
    public $users, $users_count,$total_users,$sales_count;
    public function render()
    {
        return view('livewire.home');
    }

    public function mount()
    {
        $this->total_users = count(AppUser::all());

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
    }
}
