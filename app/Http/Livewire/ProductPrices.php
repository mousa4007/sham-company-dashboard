<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductPrices extends Component
{

    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $selectedRows, $checked, $paginateNumber, $searchTerm, $from, $to, $sell_price, $buy_price;

    public function render()
    {
        return view('livewire.products.product-prices', ['products' => $this->products]);
    }

    // DB::table('app_users')->insert(['name'=>'mousa ali','email'=>'mousa123@gmail.com','password'=>Hash::make('12341234'),'balance'=>'22'])

    public function mount()
    {
        $this->paginateNumber = 5;
    }

    public function updatedChecked($value)
    {
        if ($value) {
            $this->selectedRows = $this->products->pluck('id');
        } else {
            $this->reset(['selectedRows', 'checked']);
        }
    }

    public function getProductsProperty()
    {
        if ($this->from) {
            return Product::where('is_direct', false)
                ->whereDate('created_at', '>=', $this->from)
                ->whereDate('created_at', '<=', $this->to)
                ->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('sell_price', 'like', '%' . $this->searchTerm . '%');
                })->latest()->paginate($this->paginateNumber);
        } else {
            return Product::where('is_direct', false)
                ->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('sell_price', 'like', '%' . $this->searchTerm . '%');
                })->latest()->paginate($this->paginateNumber);
        }
    }

    public function updateBuyPrice($product_id)
    {

        // dd($this->buy_price[$product_id]);

        $product = Product::find($product_id);

        // dd($product);

        $product->update([
            'buy_price' => $this->buy_price[$product_id]
        ]);

        $this->dispatchBrowserEvent('hide-update-modal', ['message' => 'تم التعديل بنجاح']);
    }

    public function updateSellPrice($product_id)
    {

        // dd($this->buy_price[$product_id]);

        $product = Product::find($product_id);

        // dd($product);

        $product->update([
            'sell_price' => $this->sell_price[$product_id]
        ]);

        $this->dispatchBrowserEvent('hide-update-modal', ['message' => 'تم التعديل بنجاح']);
    }
}
