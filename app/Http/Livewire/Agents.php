<?php

namespace App\Http\Livewire;

use App\Mail\EmailVerify;
use App\Models\Agent;
use App\Models\AppUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithPagination;

class Agents extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $name,
        $email,
        $password,
        $balance,
        $permission,
        $ids,
        $searchTerm,
        $checked = false,
        $selectedRow = [],
        $paginateNumber,
        $date,
        $from,
        $to,
        $orders,
        $returns,
        $email_confirmation,
        $phone, $address, $app_user_id;



    public function render()
    {

        return view('livewire.agents.agents', [
            'appUsers' => $this->app_users,
            'agents' => Agent::all(),
            'users' => AppUser::whereRoleIs('super-user')->get()
        ]);
    }

    public function mount()
    {
        // $this->from = Carbon::today()->format('Y-m-d');
        // $this->to = Carbon::today()->format('Y-m-d');
        // $this->orders = auth()->user()->orders;
        $this->paginateNumber = 5;
    }

    public function store()
    {
        $data = $this->validate([
            'name' => 'required|string|min:4',
            'email' => 'required|email|unique:app_users,email',
            'password' => 'required|min:6',
            'phone' => 'required',
            'address' => 'required',
            'app_user_id' => 'required',
        ]);



        // $rand = new EmailVerify();

        $agent = Agent::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'password' => Hash::make($data['password']),
            'app_user_id' => $data['app_user_id']
        ]);


        $user = AppUser::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'password' => Hash::make($data['password']),
            'agent_id' => $agent->id
        ]);

        $user->syncRoles(['agent']);

        $email = $data['email'];

        // Mail::to($email)->send($rand);

        $this->resetData();


        $this->dispatchBrowserEvent('hide-create-modal', ['message' => 'تم إضافة العميل بنجاح']);
        // $this->dispatchBrowserEvent('email-sended', ['message' => 'تم إرسال الإيميل بنجاح']);
    } //end store function

    public function resetData()
    {
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->permission = '';
        $this->phone = '';
        $this->address = '';
    } //end resetData function

    public function edit(AppUser $appUser)
    {
        $this->ids = $appUser->id;
        $this->name = $appUser->name;
        $this->email = $appUser->email;
        $this->phone = $appUser->phone;
        $this->address = $appUser->address;
        // $this->password = $appUser->password;
        // $this->permission = $appUser->hasRole('super-user') ? 1 : 2;
        // $this->orders = count($appUser->orders);
        // $this->returns = count($appUser->returns);
    } //end edit function

    public function update()
    {
        $appUser = AppUser::find($this->ids);


        $data = $this->validate([
            'name' => 'required|string|min:4',
            'email' => 'required|email',
            'password' => 'min:6',
            'phone' => 'required',
            'address' => 'required',
        ]);

        if($this->password == null){
            $appUser->update([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'address' => $data['address'],
            ]);
        }else{
            $appUser->update([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'address' => $data['address'],
                'password' => Hash::make($data['password']),
            ]);
        }



        // if ($data['permission'] == 1) {
        //     $appUser->syncRoles(['super-user']);
        // } elseif ($data['permission'] == 2) {
        //     $appUser->syncRoles(['user']);
        // }
        $email = $data['email'];

        if ($this->email != $email) {

            Mail::to($email)->send(new EmailVerify($email));

            $this->dispatchBrowserEvent('email-sended', ['message' => 'تم إرسال الإيميل بنجاح']);
        }

        $this->dispatchBrowserEvent('hide-update-modal', ['message' => 'تم التعديل بنجاح']);

        $this->resetData();
    } //end resetData function

    public function updatedChecked($value)
    {
        if ($value) {
            $this->selectedRow = $this->app_users->pluck('id');
        } else {
            $this->reset(['selectedRow', 'checked']);
        }
    }

    public function destroy()
    {
        // dd($this->selectedRow);
        AppUser::whereIn('id', $this->selectedRow)->each(function ($q) {
            $agent_ids = AppUser::whereIn('id', $this->selectedRow)->pluck('agent_id');
            Agent::whereIn('id', $agent_ids)->delete();
            // Agent::find($q->where('agent_id', '!=', '')->first()->agent_id)->delete();
        });

        AppUser::whereIn('id', $this->selectedRow)->delete();



        $this->reset(['checked']);

        return $this->dispatchBrowserEvent('hide-delete-modal', ['message' => 'تم الحذف بنجاح']);
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

    public function disable()
    {
        AppUser::whereIn('id', $this->selectedRow)->update([
            'status' => 'disabled'
        ]);
    }

    public function activate()
    {
        AppUser::whereIn('id', $this->selectedRow)->update([
            'status' => 'active'
        ]);
    }
}
