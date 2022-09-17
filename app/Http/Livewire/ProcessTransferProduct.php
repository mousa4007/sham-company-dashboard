<?php

namespace App\Http\Livewire;

use App\Models\AppUser;
use App\Models\Message;
use App\Models\Order;
use App\Models\Product;
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

        $user = AppUser::find($this->app_user_id);
        $product = Product::find($this->product_id);

        Order::create([
            'app_user_id' => $this->app_user_id,
            'product_id' => $this->product_id,
            'product' => $product->name,
        ]);

        Sale::create([
            'product' => $product->name,
            'product_id' => $this->product_id,
        ]);

        $user->update([
            'balance' => $user->balance - $this->amount
        ]);

        TransferProduct::find($this->transfer_id)->update([
            'status' => 'accepted',
        ]);

        Message::create([
            'app_user_id' => $this->app_user_id,
            'message' => $this->message
        ]);

        $this->dispatchBrowserEvent('hide-create-modal', ['message' => ' تم الموافقة على عملية التحويل وخصم المبلغ من المستخدم']);
    }

    public function rejectTransfer()
    {

        $user = AppUser::find($this->app_user_id);

        $user->update([
            'balance' => $user->balance + $this->amount
        ]);

        TransferProduct::find($this->transfer_id)->update([
            'status' => 'rejected',
        ]);

        Message::create([
            'app_user_id' => $this->app_user_id,
            'message' => $this->message
        ]);

        $this->dispatchBrowserEvent('hide-create-modal', ['message' => ' تم رفض عملية التحويل وإرجاع المبلغ إلى المستخدم']);
    }
}
