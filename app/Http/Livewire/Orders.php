<?php

namespace App\Http\Livewire;

use App\Models\Order;
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
        $checked = false,
        $selectedRows = [],
        $from,
        $to,
        $paginateNumber = 10;

    public function render()
    {
        return view('livewire.orders.orders',[
            'orders' => $this->orders
        ]);
    }

    public function getOrdersProperty(){
        return Order::latest()->paginate($this->paginateNumber);
    }



}
