<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Discount;
use Livewire\Component;

class UpdateProductsDiscount extends Component
{
    public $products = [], $newPercentage, $discountId;

    public function render()
    {
        return view(
            'livewire.discounts.update_products_discount',
            ['categories' => Category::latest()->paginate(7), 'discounts' => Discount::all()]
        );
    }

    public function edit($id)
    {
        $this->products = Category::find($id)->products;

        return $this->products;
    }

    public function update($ids)
    {
        $this->discountId = $ids;
        $discount = Discount::find($ids);

        $discount->update([
            'percentage' => $this->newPercentage[$this->discountId]
        ]);

        $this->dispatchBrowserEvent('hide-update-modal', ['message' => 'تم التعديل بنجاح']);
    }
}
    