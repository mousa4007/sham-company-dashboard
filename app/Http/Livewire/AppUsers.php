<?php

namespace App\Http\Livewire;

use App\Mail\EmailVerify;
use App\Models\AppUser;
use App\Models\Discount;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithPagination;
use PhpOffice\PhpSpreadsheet\Calculation\Financial\Securities\Price;

class AppUsers extends Component
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
        $phone, $address, $discount;



    public function render()
    {

        return view('livewire.app_users.app-users', [
            'appUsers' => $this->app_users,
            'discounts' => Discount::all()
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
            'permission' => 'required',
            'phone' => 'required',
            'address' => 'required',
            // 'discount' => 'nullable',
        ]);

        // dd($data);



        // $rand = new EmailVerify();

        $user = AppUser::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            // 'discount' => $data['discount'],
            'password' => Hash::make($data['password']),
        ]);

        if ($data['permission'] == 1) {
            $user->syncRoles(['super-user']);
        } elseif ($data['permission'] == 2) {
            $user->syncRoles(['user']);
        }
        // $email = $data['email'];

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
        $this->password = $appUser->password;
        $this->discount = $appUser->discount;
        $this->permission = $appUser->hasRole('super-user') ? 1 : 2;
        $this->orders = count($appUser->orders);
        $this->returns = count($appUser->returns);
    } //end edit function

    public function update()
    {
        $appUser = AppUser::find($this->ids);

        $data = $this->validate([
            'name' => 'required|string|min:4',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'permission' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'discount' => 'nullable',
        ]);

        $appUser->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'discount' => $data['discount'],
            'password' => Hash::make($data['password']),
        ]);

        if ($data['permission'] == 1) {
            $appUser->syncRoles(['super-user']);
        } elseif ($data['permission'] == 2) {
            $appUser->syncRoles(['user']);
        }
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

        AppUser::whereIn('id', $this->selectedRow)->each(function ($q) {

            $agent_id = $q->agent->pluck('id');

            AppUser::whereIn('agent_id', $agent_id)->update(['status' => 'disabled']);
        });

        AppUser::whereIn('id', $this->selectedRow)->delete();



        $this->reset(['checked']);

        return $this->dispatchBrowserEvent('hide-delete-modal', ['message' => 'تم الحذف بنجاح']);
    }

    public function getAppUsersProperty()
    {
        if ($this->from) {
            return AppUser::query()
                // ->where('status','active')
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
                ->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('email', 'like', '%' . $this->searchTerm . '%');
                })
                ->whereRoleIs('super-user')
                ->orWhereRoleIs('user')
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
