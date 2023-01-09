<?php

namespace App\Http\Livewire;

use App\Models\AppUser;
use App\Models\Message;
use Livewire\Component;
use Livewire\WithPagination;

class Messages extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $title, $body, $paginateNumber,
        $selectedRows = [], $checked = false,
        $from, $to, $searchTerm, $targetUser, $notification,$message;

    protected $rules = [
        'title' => 'required',
        'body' => 'required',
    ];

    public function render()
    {
        return view('livewire.messages.messages', [
            'messages' => $this->messages,
            'app_users' => AppUser::all()
        ]);
    }

    public function mount()
    {
        $this->paginateNumber = 5;
        $this->targetUser = 'all';
    }

    public function resetData()
    {
        $this->title = '';
        $this->body = '';
    }

    public function store()
    {

        $this->validate();

        if ($this->targetUser == 'all') {
            $users = AppUser::all();

            foreach ($users as  $user) {
                Message::create([
                    'title' =>  $this->title,
                    'body' => $this->body,
                    'type' => 'general',
                    'app_user_id'=>$user->id,
                ]);
            }

            $SERVER_API_KEY = 'AAAAQN0Zyos:APA91bGqasEAoN91dFRkQddMYO0kTWuvYqz7QKezNt_BlT-rzy8UFXBb4yiTcHH0Jx3JqFzb5-SqXKs_R6WHl38mmme7m0vlffhdr6jYGTXRpsZZMLU2r2NmQrcEnUdYeOhDRWhen-oy';

            $tokens = $users->pluck('fcmToken');
            $data = [
                "registration_ids" => $tokens,

                "notification" => [

                    "title" => $this->title,

                    "body" => $this->body,

                    "sound" => "default" // required for sound on ios

                ],

            ];

            $dataString = json_encode($data);

            $headers = [

                'Authorization: key=' . $SERVER_API_KEY,

                'Content-Type: application/json',

            ];

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');

            curl_setopt($ch, CURLOPT_POST, true);

            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

            $response = curl_exec($ch);

            curl_close($ch);


            $this->dispatchBrowserEvent('hide-create-modal', ['message' => 'تمت الإضافة بنجاح']);
        } else {
            $user = AppUser::find($this->targetUser);

              Message::create([
                    'title' =>  $this->title,
                    'body' => $this->body,
                    'type' => 'special',
                    'app_user_id'=>$user->id,
                ]);

            $SERVER_API_KEY = 'AAAAEBic3Dk:APA91bFQrDq1MlwA-NuBRDl5QxPnWi3SCJfovvmI4WljNw_HmQt2X26VmQ5SEtlCwYrkYBvIqsYA5AKJmwjSF07hCCTNbRvVWXq_pK077VqwCqVaDZOiJwB-iDnPKAl5DV_7lH8-ZB-d';

            $data = [
                "registration_ids" => [$user->fcmToken],

                "notification" => [

                    "title" => $this->title,

                    "body" => $this->body,

                    "sound" => "default" // required for sound on ios

                ],

            ];

            $dataString = json_encode($data);

            $headers = [

                'Authorization: key=' . $SERVER_API_KEY,

                'Content-Type: application/json',

            ];

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');

            curl_setopt($ch, CURLOPT_POST, true);

            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

            $response = curl_exec($ch);

            curl_close($ch);


            $this->dispatchBrowserEvent('hide-create-modal', ['message' => 'تمت الإضافة بنجاح']);
        }
    }

    public function updatedChecked($value)
    {
        if ($value) {
            return $this->selectedRows = $this->messages->pluck('id');
        } else {
            $this->reset('selectedRows', 'checked');
        }
    }

    public function getMessagesProperty()
    {
        if ($this->from) {
            return Message::query()
                ->whereDate('created_at', '>=', $this->from)
                ->whereDate('created_at', '<=', $this->to)
                ->where('title', 'like', '%' . $this->searchTerm . '%')
                ->latest()
                ->paginate($this->paginateNumber);
        } else {
            return Message::query()
                ->where('title', 'like', '%' . $this->searchTerm . '%')
                ->latest()
                ->paginate($this->paginateNumber);
        }
    }

    public function info(Message $message)
    {
        $this->message = $message;
    }

    public function destroy()
    {
        Message::whereIn('id', $this->selectedRows)->delete();

        $this->reset(['checked']);

        return $this->dispatchBrowserEvent('hide-delete-modal', ['message' => 'تم الحذف بنجاح']);
    }
}
