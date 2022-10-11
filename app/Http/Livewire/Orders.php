<?php

namespace App\Http\Livewire;

use App\Models\AppUser;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
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
        $products;

    public function render()
    {
        return view('livewire.orders.orders',[
            'orders' => $this->orders,
            'products' =>$this->products,
            'categories' => $this->categories,
        ]);
    }
    public function mount()
    {

        $this->paginateNumber = 10;
        $this->products = Product::all();
        $this->categories =    Category::all();
    }

    public function getOrdersProperty(){
        // return Order::latest()->paginate($this->paginateNumber);

        $query = Order::query();

        $query->when($this->from,function($q){
            return $q->where('created_at','>=',$this->from)
            ->where('created_at','<=',$this->to);
        });

        $query->when($this->product_id,function($q){
            return $q->where('product_id',$this->product_id);
        });

        $query->when($this->product_id,function($q){
            // $ids = Category::pluck
            return $q->where('product_id',$this->product_id);
        });

        $query->when($this->searchTerm,function($q){
            return $q->where('id',$this->searchTerm);
        });


        return $query->paginate($this->paginateNumber);
    }



}
