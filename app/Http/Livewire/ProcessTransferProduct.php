<?php

namespace App\Http\Livewire;

use App\Models\Agent;
use App\Models\AppUser;
use App\Models\Discount;
use App\Models\Message;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Product;
use App\Models\Profit;
use App\Models\Sale;
use App\Models\TransferProduct;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ProcessTransferProduct extends Component
{
    use  WithPagination;
    use  WithFileUploads;
    protected $paginationTheme = 'bootstrap';

    public
        $searchTerm,
        $checked = false,
        $selectedRows = [],
        $from,
        $to,
        $created_at,
        $paginateNumber = 10,
        $app_user_id, $product_id,
        $address,
        $amount,
        $message, $products, $transfer_id,
        $is_accept, $is_reject;

    public function render()
    {
        return view('livewire.process_transfer_product.transfer-product', [
            'transferProducts' => $this->transferProducts,
            'rejectedTransferProducts' => $this->rejectedTransferProducts,
            'acceptedTransferProducts' => $this->acceptedTransferProducts,
            'app_users' => AppUser::all(),
            'products' => $this->products
        ]);
    }

    public function mount()
    {
        $this->products = Product::all();
    }

    public function updatedChecked($value)
    {
        if($value){
            $this->selectedRows = $this->transferProducts->pluck('id');
        }else{
            $this->reset(['selectedRows','checked']);
        }
    }

    public function getTransferProductsProperty()
    {
        $query = TransferProduct::query()->where('status', 'ignored');

        $query->when($this->from, function ($q) {
            return $q->whereDate('created_at', '>=', $this->from)
                ->whereDate('created_at', '<=', $this->to)
                ->latest();
        });

        $query->when($this->searchTerm, function ($q) {
            return $q->where('id', $this->searchTerm)
                ->latest();
        });

        return $query->paginate($this->paginateNumber);
    }

    public function getAcceptedTransferProductsProperty()
    {

        $query = TransferProduct::query()->where('status', 'accepted');

        $query->when($this->from, function ($q) {
            return $q->where('status', 'accepted')
                ->whereDate('created_at', '>=', $this->from)
                ->whereDate('created_at', '<=', $this->to)
                ->latest();
        });

        $query->when($this->searchTerm, function ($q) {
            return $q->where('status', 'accepted')
                ->where('id', $this->searchTerm)
                ->latest();
        });

        return $query->paginate($this->paginateNumber);
    }

    public function getRejectedTransferProductsProperty()
    {
        $query = TransferProduct::query()->where('status', 'rejected');

        $query->when($this->from, function ($q) {
            return $q->where('status', 'rejected')
                ->whereDate('created_at', '>=', $this->from)
                ->whereDate('created_at', '<=', $this->to)
                ->latest();
        });

        $query->when($this->searchTerm, function ($q) {
            return $q->where('status', 'rejected')
                ->where('id', $this->searchTerm)
                ->latest();
        });

        return $query->paginate($this->paginateNumber);
    }

    public function createAcceptMessage(TransferProduct $product)
    {
        $this->is_accept = true;
        $this->transfer_id =  $product->id;
        $this->app_user_id = $product->app_user_id;
        $this->product_id = $product->product_id;
        $this->address = $product->address;
        $this->amount = $product->amount;
        $this->created_at = $product->created_at;
        $name = $this->products->find($this->product_id)->name;

        $this->message = "العملية مكتملة ✅  \r\nالرقم : $this->address  \r\nالمنتج : $name  \r\nالمبلغ :  $this->amount";
    }

    public function createRejectMessage(TransferProduct $product)
    {
        $this->is_reject = true;
        $this->transfer_id =  $product->id;
        $this->app_user_id = $product->app_user_id;
        $this->product_id = $product->product_id;
        $this->address = $product->address;
        $this->amount = $product->amount;
        $this->created_at = $product->created_at;
        $name = $this->products->find($this->product_id)->name;

        $this->message = "العملية مرفوضة ⛔️  \r\nالرقم : $this->address  \r\nالمنتج : $name  \r\nالمبلغ :  $this->amount";
    }

    public function acceptTransfer()
    {
        TransferProduct::whereIn('id',$this->selectedRows)->each(function($q){

            $user = AppUser::find($q->app_user_id);
            $product = Product::find($q->product_id);
            $order = Order::find($q->order_id);

            if ($user->hasRole('super-user') || $user->hasRole('user')) {
                if ($user->discount != null) {
                    if (count(Discount::find($user->discount)->exceptions)>0) {

                        $exception = Discount::find($user->discount)->exceptions;

                        // return $exception->first()->price;
                        $exceptions_ids = $exception->pluck('product_id')->toArray();

                        if (in_array($order->product_id, $exceptions_ids)) {
                            $profit = $product->sell_price - $exception->first()->price;

                            Profit::create([
                                'order_id'=> $order->id,
                                'app_user_id' => $user->id,
                                'agent_id' => null,
                                'product_id' => $order->product_id,
                                'profit' => $profit,
                                'message' => $profit . '$ مربح من شراء منتج ' . Product::find($order->product_id)->name
                            ]);

                            $user->update([
                                'total_profits' => $user->total_profits + $profit,
                            ]);
                        }
                    } else {
                        $profit = abs($product->sell_price * Discount::find($user->discount)->percentage / 100);

                        Profit::create([
                            'order_id'=> $order->id,
                            'app_user_id' => $user->id,
                            'agent_id' => null,
                            'product_id' => $order->product_id,
                            'profit' => $profit,
                            'message' => $profit . '$ مربح من شراء منتج ' . Product::find($order->product_id)->name
                        ]);

                        $user->update(['total_profits' => $user->total_profits + $profit]);
                    }
                }
            }


            if ($user->hasRole('agent')) {

                $agent = Agent::find($user->agent_id);

                if ($agent->user->discount != null) {
                    if (count(Discount::find($agent->user->discount)->exceptions)>0) {

                        $exception = Discount::find($agent->user->discount)->exceptions;

                        // return $exception->first()->price;
                        $exceptions_ids = $exception->pluck('product_id')->toArray();

                        if (in_array($order->product_id, $exceptions_ids)) {
                            $profit = $product->sell_price - $exception->first()->price;

                            Profit::create([
                                'order_id'=> $order->id,
                                'app_user_id' => $agent->user->id,
                                'agent_id' => $user->agent_id,
                                'product_id' => $order->product_id,
                                'profit' => $profit,
                                'message' => $product->sell_price - $exception->first()->price . '$ مربح من شراء وكيل منتج ' . Product::find($order->product_id)->name
                            ]);

                            $agent->user->update(['total_profits' =>  $agent->user->total_profits + $profit]);
                        }
                    }else {
                        $profit = abs($product->sell_price * Discount::find($agent->user->discount)->percentage / 100);

                        Profit::create([
                            'order_id'=> $order->id,
                            'app_user_id' => $agent->user->id,
                            'agent_id' => $user->agent_id,
                            'product_id' => $order->product_id,
                            'profit' => $profit,
                            'message' => abs($product->sell_price * Discount::find($agent->user->discount)->percentage / 100) . '$ مربح من شراء وكيل منتج ' . Product::find($order->product_id)->name
                        ]);
                        $agent->user->update(['total_profits' => $agent->user->total_profits + $profit]);
                    }
                }
            }

           $q->update([
            'status'=>'accepted'
           ]);

            $order->update([
                'transfer_status' => 'accepted',
            ]);

            Sale::create([
                'product' => $product->name,
                'product_id' => $product->id,
            ]);

            Notification::create([
                'title' => 'موافقة على عملية تحويل',
                'app_user_id' => $user->id,
                'message' => "العملية مكتملة ✅  \r\nالرقم : $order->product  \r\nالمنتج : $product->name  \r\nالمبلغ :  $product->sell_price"

            ]);

            $user->notificationsCount->update([
                'notifications_count' =>  $user->notifications_count + 1
            ]);

            $this->dispatchBrowserEvent('hide-create-modal', ['message' => ' تم الموافقة على عملية التحويل']);

        });

          }

    public function rejectTransfer()
    {
        TransferProduct::whereIn('id',$this->selectedRows)->each(function($q){

            $user = AppUser::find($q->app_user_id);
            $product = Product::find($q->product_id);
            $order = Order::find($q->order_id);


           $q->update([
            'status'=>'rejected'
           ]);

            $order->update([
                'transfer_status' => 'rejected',
            ]);

            $user->update([
                'balance' => $user->balance + $order->price,
                'outgoingBalance' => $user->outgoingBalance - $order->price,
            ]);

            $q->update([
                'status' => 'rejected',
            ]);


            Notification::create([
                'title' => 'رفض عملية تحويل',
                'app_user_id' => $user->id,
                'message' => "العملية مرفوضة ⛔️  \r\nالرقم : $order->product  \r\nالمنتج : $product->name  \r\nالمبلغ :  $order->price"
            ]);

            $user->notificationsCount->update([
                'notifications_count' =>  $user->notifications_count + 1
            ]);

            $this->dispatchBrowserEvent('hide-delete-modal', ['message' => ' تم رفض عملية التحويل وإرجاع المبلغ إلى المستخدم']);

        });

    }
}
