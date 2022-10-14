<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Discount;
use App\Models\DiscountException;
use Livewire\Component;
use Livewire\WithPagination;

class ProductsDiscounts extends Component
{
    use WithPagination;
    public $paginationTheme = 'bootstrap';

    public $products = [];
    public $price;
    public $product_id;
    public $discount_id;
    public $discountException;


    public function render()
    {
        return view(
            'livewire.discounts.products_discount',
            [
                'categories' => Category::latest()->paginate(7),
                'discounts' => Discount::all()
            ]
        );
    }

    public function mount()
    {
        $this->discountException = DiscountException::all();
    }



    public function edit($id)
    {
        $this->products = Category::find($id)->products;
        return $this->products;
    }

    public function createDiscountException($product_id, $discount_id)
    {
        $discountException = DiscountException::where('discount_id', $discount_id)->where('product_id', $product_id)->first();

        if ($discountException) {
            $discountException->update([
                'discount_id' => $discount_id,
                'price' => $this->price[$product_id][$discount_id]
            ]);
        } else {
            DiscountException::create([
                'product_id' => $product_id,
                'discount_id' => $discount_id,
                'price' => $this->price[$product_id][$discount_id]
            ]);
        }

        $this->dispatchBrowserEvent('hide-create-modal', ['message' => 'تم التعديل بنجاح']);
    }

    public function resetPercentage()
    {
        DiscountException::query()->delete();
        $this->price = '';
    }
}
