<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class Stock extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $title, $body, $paginateNumber = 10,
        $selectedRows = [], $checked = false,
        $from, $to, $searchTerm, $targetUser, $notification,$message;

    public function render()
    {
        return view('livewire.stock.stock',['products'=>$this->products]);
    }

    public function getProductsProperty()
    {
        if ($this->from) {
            return Product::where('is_direct', false)
            ->where('is_transfer',null)

                ->whereDate('created_at', '>=', $this->from)
                ->whereDate('created_at', '<=', $this->to)
                ->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('sell_price', 'like', '%' . $this->searchTerm . '%');
                })->latest()->paginate($this->paginateNumber);
        } else {
            return Product::where('is_direct', false)
                ->where('is_transfer',null)
                ->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('sell_price', 'like', '%' . $this->searchTerm . '%');
                })->latest()->paginate($this->paginateNumber);
        }
    }
}
