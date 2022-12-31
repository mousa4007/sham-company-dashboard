<?php

namespace App\Http\Livewire;

use App\Exports\OrdersExport;
use App\Models\AppUser;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class Orders extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $name,
        $image_url,
        $image_url_preview,
        $price,
        $ids,
        $category_id,
        $searchTerm,
        $userIdSearchTerm,
        $description,
        $currency,
        $arrangement,
        $from,
        $to,
        $paginateNumber = 10,
        $categories,
        $product_id,
        $app_user_id,
        $selectedRows = [],
        $checked = false,
        $users,
        $products;


    public function render()
    {
        return view('livewire.orders.orders',[
            'orders' => $this->orders,
            'products' =>$this->products,
            'categories' => $this->categories,
            'users' => $this->users,
        ]);
    }

    public function mount()
    {

        $this->paginateNumber = 10;
        $this->products = Product::all();
        $this->categories =    Category::all();
        $this->users =    AppUser::all();
        $this->from = Carbon::today();
        $this->to = Carbon::tomorrow();
    }

    public function updatedChecked($value)
    {
        if ($value) {
            $this->selectedRows = $this->orders->pluck('id');
        } else {
            $this->reset(['checked', 'selectedRows']);
        }
    }

    public function getOrdersProperty(){

        $query = Order::query();

        $query->when($this->from,function($q){
            return $q->where('created_at','>=',$this->from)
            ->where('created_at','<=',$this->to);
        });

        $query->when($this->product_id,function($q){
            return $q->where('product_id',$this->product_id);
        });

        $query->when($this->app_user_id,function($q){
            return $q->where('app_user_id',$this->app_user_id);
        });

        $query->when($this->category_id, function ($q) {

            $ids = Category::find($this->category_id)->products->pluck('id');

            return $q->whereIn('product_id', $ids)->get();
        });

        $query->when($this->searchTerm,function($q){
            return $q->where('id',$this->searchTerm);
        });

        $query->when($this->userIdSearchTerm,function($q){
            return $q->where('app_user_id',$this->userIdSearchTerm);
        });

        return $query->latest()->paginate($this->paginateNumber);
    }

    public function export()
    {
        return (new OrdersExport($this->from,$this->to, $this->product_id,$this->category_id,$this->app_user_id,$this->searchTerm,$this->userIdSearchTerm))
        ->download('orders.xls');
    }


}
