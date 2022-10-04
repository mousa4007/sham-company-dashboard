<?php

namespace App\Http\Livewire;

use App\Exports\ReturnsExport;
use App\Models\Agent;
use App\Models\AppUser;
use App\Models\Discount;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Product;
use App\Models\Profit;
use App\Models\Returns as ModelsReturns;
use App\Models\StockedProduct;
use App\Models\User;
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
        $checked,
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
            'orders'=>Order::all()
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
            $product =  Product::find($return->product_id);
            $user = AppUser::find($return->app_user_id);

            if ($return->status != 'accepted') {
                if ($return->agent_id) {
                    //find super user from agent id
                    $superUser = Agent::find($return->agent_id)->user;

                    //find super user discount
                    $exception = Discount::find($superUser->discount)->exceptions;

                    // get discount excetion ids
                    $exceptions_ids = $exception->pluck('product_id')->toArray();

                    if ($user->hasRole('agent')) {
                        $agent = Agent::find($user->agent_id);
                        if (in_array($return->product_id, $exceptions_ids)) {

                            Profit::create([
                                'app_user_id' => $agent->user->id,
                                'agent_id' => $user->agent_id,
                                'product_id' => $return->product_id,
                                'profit' =>  - ($product->price - $exception->first()->price)
                            ]);
                        } else {
                            Profit::create([
                                'app_user_id' => $agent->user->id,
                                'agent_id' => $user->agent_id,
                                'product_id' => $return->product_id,
                                'profit' => - ($product->price * Discount::find($user->discount)->percentage / 100)
                            ]);
                        }
                    }

                    Order::where('product', $return->return)->update([
                        'product' => 'تم الإرجاع'
                    ]);

                    $return->update([
                        'status' => 'accepted'
                    ]);

                    $user->update([
                        'balance' => $user->balance + $product->price
                    ]);

                    Notification::create([
                        'app_user_id' => $return->app_user_id,
                        'message' => ' تم استرجاع قيمة المنتج  ' . $product->name,
                    ]);

                    $this->dispatchBrowserEvent('hide-create-modal', ['message' => 'تمت الموافقة']);
                } else {

                    $return->update([
                        'status' => 'accepted'
                    ]);

                    $user->update([
                        'balance' => $user->balance + $product->price
                    ]);

                    Notification::create([
                        'app_user_id' => $return->app_user_id,
                        'message' => ' تم استرجاع قيمة المنتج  ' . $product->name,
                    ]);

                    $this->dispatchBrowserEvent('hide-create-modal', ['message' => 'تمت الموافقة']);
                }
            }
        });
    }

    public function ignore()
    {
        ModelsReturns::whereIn('id', $this->selectedRows)->update([
            'status' => 'ignored'
        ]);
    }

    public function reject()
    {

        ModelsReturns::whereIn('id', $this->selectedRows)->update([
            'status' => 'rejected'
        ]);
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

        return $query->paginate($this->paginateNumber);
    }

    public function destroy()
    {
        ModelsReturns::whereIn('id', $this->selectedRows)->delete();

        $this->dispatchBrowserEvent('hide-delete-modal', ['message' => 'تم الحذف بنجاح']);
    }


    public function export()
    {
        return (new ReturnsExport($this->selectedRows))->download('المرتجعات.xls');
        // return Excel::download(new ReturnsExport, 'المرتجعات.xlsx');
    }
}
