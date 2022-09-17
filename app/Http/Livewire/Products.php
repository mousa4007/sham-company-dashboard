<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\DiscountException;
use App\Models\Product;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Products extends Component
{
    use WithPagination;
    use WithFileUploads;
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
        $paginateNumber;

    protected $rules = [
        'name' => 'required|string',
        'image_url' => 'required',
        'description' => 'required',
        'currency' => 'required',
        // 'price' => 'required|numeric|min:0|max:1000',
        'category_id' => 'required|integer',
    ];

    public function render()
    {
        return view(
            'livewire.products.products',
            [
                'products' => $this->products,

                'categories' => Category::latest()->get()
            ]
        );
    }

    public function mount()
    {
        $this->currency =  1;
        $this->paginateNumber = 5;
    }

    public function resetData()
    {
        $this->name = '';
        $this->image_url = '';
        $this->price = '';
    }

    public function store()
    {
        $data = $this->validate();

        $result = $data['image_url']->storeOnCloudinary();

        $product = Product::create([
            'name' => $data['name'],
            'image_url' => $result->getSecurePath(),
            'image_id' => $result->getPublicId(),
            // 'price' => $data['price'],
            'category_id' => $data['category_id'],
            'arrangement' => $this->arrangement != '' ? $this->arrangement : 1,
            'description' => $this->description,
            'currency' => $this->currency,
            'is_direct' => false,
        ]);

        DiscountException::create([
            'product_id' => $product->id,
            'discount_id' => null,
            'price' => null
        ]);


        $this->dispatchBrowserEvent('hide-create-modal', ['message' => 'تمت الإضافة بنجاح']);
    } //end store fucntion

    public function edit(Product $product)
    {
        $this->ids = $product->id;
        $this->name = $product->name;
        $this->image_url_preview = $product->image_url;
        $this->price = $product->price;
        $this->category_id = $product->category_id;
        $this->arrangement = $product->arrangement;
        $this->description = $product->description;
        $this->currency = $product->currency;
        $this->image_url = '';
    } //end edit fucntion

    public function update()
    {
        $data = $this->validate([
            'name' => 'required|string',
            // 'price' => 'required',
            'category_id' => 'required|integer',
            'image_url' => 'nullable',
            'description' => 'required',
            'currency' => 'required'
        ]);

        $product = Product::find($this->ids);

        if ($data['image_url'] == null) {
            $product->update([
                'name' => $data['name'],
                // 'price' => $data['price'],
                'category_id' => $data['category_id'],
                'description' => $data['description'],
                'currency' => $data['currency'],
                'arrangement' => $this->arrangement != '' ? $this->arrangement : 1,
            ]);
        } else {

            $result = $data['image_url']->storeOnCloudinary();

            $product->update([
                'name' => $data['name'],
                'image_url' => $result->getSecurePath(),
                'image_id' => $result->getPublicId(),
                // 'price' => $data['price'],
                'category_id' => $data['category_id'],
                'description' => $data['description'],
                'currency' => $data['currency'],
                'arrangement' => !is_null($this->arrangement) ? $this->arrangement : 1,

            ]);
        }

        $this->dispatchBrowserEvent('hide-update-modal', ['message' => 'تم التعديل بنجاح']);
    } //end update fucntion

    public function confirmProductRemoval($id)
    {
        $this->ids = $id;
    } //end confirmProductRemoval fucntion

    public function delete()
    {

        $product = Product::find($this->ids);

        Cloudinary::destroy($product->image_id);

        $product->delete();

        $this->dispatchBrowserEvent('hide-delete-modal', ['message' => 'تم الحذف بنجاح']);
    } //end delete function

    public function destroy()
    {
        Product::whereIn('id', $this->selectedRows)->delete();

        return $this->dispatchBrowserEvent('hide-delete-modal', ['message' => 'تم الحذف بنجاح']);
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

    public function disable()
    {
        Product::whereIn('id', $this->selectedRows)->update([
            'status' => 'disabled'
        ]);
    }

    public function activate()
    {
        Product::whereIn('id', $this->selectedRows)->update([
            'status' => 'active'
        ]);
    }

    public function available()
    {
        Product::whereIn('id', $this->selectedRows)->update([
            'available' => 'available'
        ]);
    }

    public function unavailable()
    {
        Product::whereIn('id', $this->selectedRows)->update([
            'available' => 'unavailable'
        ]);
    }
}
