<?php

namespace App\Http\Livewire;

use App\Exports\ReturnsExport;
use App\Models\Agent;
use App\Models\AppUser;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Product;
use App\Models\Profit;
use App\Models\Returns as ModelsReturns;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Concerns\Exportable;

class Returns extends Component
{
    use WithPagination;
    use Exportable;

    public $paginationTheme = 'bootstrap';
    public $return,
        $returner,
        $reason,
        $status,
        $users,
        $products,
        $product_id,
        $app_user_id,
        $search_product_id,
        $returnId,
        $checked = false,
        $selectedRows = [],
        $paginateNumber,
        $from,
        $to, $searchTerm,
        $processingStatus = [];




    public function mount()
    {

        // dd($this->returns);
        $this->users = AppUser::all();
        $this->products = Product::all();
        // $this->returner = AppUser::take(1)->first()->id;
        // $this->product_id = Product::take(1)->first()->id;
        $this->paginateNumber = 10;

        // $this->processingStatus = ['all'];
    }
    public function render()
    {
        // dd('90222547460\n 90040056043' == '90222547460 90040056043');
        return view('livewire.returns.returns', [
            'returns' => $this->returns,
            'app_users' => AppUser::all(),
            'orders' => Order::all()
        ]);
    }

    public function edit($id)
    {
        $return = ModelsReturns::find($id);

        $this->returnId =  $return->id;
        $this->return = $return->return;
        $this->returner = $return->app_user_id;
        $this->reason = $return->reason;
        $this->status = $return->status;
        $this->product_id = $return->product_id;
    }

    public function accept()
    {
        ModelsReturns::whereIn('id', $this->selectedRows)->each(function ($return) {
            if ($return->status != 'rejected' && $return->status != 'accepted') {
                Profit::where('order_id', $return->order_id)->update([
                    'profit' => 0
                ]);

                $user = AppUser::find($return->app_user_id);

                // dd($return->order_id);

                $order = Order::find($return->order_id);

                // dd($order);

                $user->update([
                    'balance' => $user->balance + $order->price,
                    'outgoingBalance' => $user->outgoingBalance - $order->price,
                    'total_profits' => $user->total_profits - $order->profit,
                ]);

                if($user->hasRole('agent')){
                    $agent = Agent::find($user->agent_id);
                    $agent->update([
                        'balance' => $agent->balance + $order->price
                    ]);
                }

                $return->update([
                    'status' => 'accepted'
                ]);

                $order->update([
                    'product' => '???? ???????? ????????????????',
                    'profit' => 0
                ]);

                Notification::create([
                    'title' => '???????????? ?????? ?????? ????????????',
                    'app_user_id' => $return->app_user_id,
                    'message' => ' ???? ?????????????? ???????? ????????????  ' . $order->product_name,
                ]);

                $user->notificationsCount->update([
                    'notifications_count' =>  $user->notifications_count + 1
                ]);

                $this->dispatchBrowserEvent('hide-create-modal', ['message' => '?????? ????????????????']);
            } else {
                $this->dispatchBrowserEvent('hide-update-modal', ['message' => ' ?????? ???????????? ?????? ?????????????? ???? ??????']);
            }
        });
    }


    public function reject()
    {
        ModelsReturns::whereIn('id', $this->selectedRows)->each(
            function ($return) {

                if ($return->status != 'rejected' && $return->status != 'accepted') {
                    $return->update([
                        'status' => 'rejected'
                    ]);

                    Notification::create([
                        'title' => '??????  ?????? ????????????',
                        'app_user_id' => $return->app_user_id,
                        'message' => ' ?????? ?????? ???????????? ???????????? ' . $return->return,
                    ]);

                    $user = AppUser::find($return->app_user_id);

                    $user->notificationsCount->update([
                        'notifications_count' =>  $user->notifications_count + 1
                    ]);
                }
                $this->dispatchBrowserEvent('hide-delete-modal', ['message' => '???? ?????? ????????????????']);
            }
        );
    }

    public function updatedChecked($value)
    {
        if ($value) {
            $this->selectedRows = $this->returns->pluck('id');
        } else {
            $this->reset(['selectedRows', 'checked']);
        }
    }

    public function getReturnsProperty()
    {
        $query = ModelsReturns::query();

        $query->when($this->processingStatus, function ($q) {
            return $q->where('status', $this->processingStatus);
        });

        $query->when($this->from, function ($q) {
            return $q->whereDate('created_at', '>=', $this->from)
                ->whereDate('created_at', '<=', $this->to);
        });

        $query->when($this->searchTerm, function ($q) {
            return $q->where('return', 'like', '%' . $this->searchTerm . '%')
                ->orWhere('id', 'like', '%' . $this->searchTerm . '%');
        });

        $query->when($this->search_product_id, function ($q) {
            return $q->where('product_id', $this->search_product_id);
        });

        $query->when($this->app_user_id, function ($q) {
            return $q->where('app_user_id', $this->app_user_id);
        });

        return $query->latest()->paginate($this->paginateNumber);
    }

    public function destroy()
    {
        ModelsReturns::whereIn('id', $this->selectedRows)->delete();

        $this->dispatchBrowserEvent('hide-delete-modal', ['message' => '???? ?????????? ??????????']);
    }


    public function export()
    {
        return (new ReturnsExport($this->selectedRows))->download('??????????????????.xls');
    }
}
