<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;

class Profile extends Component
{

    public $name, $email, $passowrd;

    public function render()
    {
        return view(
            'livewire.admin_profile.navbar',
            [
                'name' => $this->name
            ]
        );
    }

    public function edit()
    {
        dd('here');
        $admin = User::first();

        $this->name = $admin->name;
        $this->email = $admin->email;
        $this->password = $admin->password;
    }

    public function update()
    {
        $data = $this->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required'
        ]);

        User::query()->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        $this->dispatchBrowserEvent('hide-update-modal', ['message' => 'تم التعديل بنجاح']);
    }
}
