<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\DiscountException;
use App\Models\Product;
use Illuminate\Support\Facades\File;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Intervention\Image\ImageManagerStatic as Image;


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
        $this->paginateNumber = 5 ;
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

           if($data['image_url']){
                $img = $data['image_url'];
                $img_name = $img->getClientOriginalName();
                $img = Image::make($img)->resize(250,150);
                $img->save('storage/products/' . $img_name, 25);
        }

        $product = Product::create([
            'name' => $data['name'],
            'image_url' => asset('storage/products/' . $img_name),
            'image_id' => $img_name,
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


        $this->dispatchBrowserEvent('hide-create-modal', ['message' => '?????? ?????????????? ??????????']);
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

            if (File::exists(public_path('storage/products/'.explode('/' ,$this->image_url_preview)[5]))) {
                File::delete(public_path('storage/products/'.explode('/' ,$this->image_url_preview)[5]));
            }

            if($data['image_url']){
                $img = $data['image_url'];
                $img_name = $img->getClientOriginalName();
                $img = Image::make($img)->resize(250,150);
                $img->save('storage/products/' . $img_name, 25);
            }
            $product->update([
                'name' => $data['name'],
                'image_url' => asset('storage/products/'.$img_name),
                'image_id' => $img_name,
                'category_id' => $data['category_id'],
                'description' => $data['description'],
                'currency' => $data['currency'],
                'arrangement' => !is_null($this->arrangement) ? $this->arrangement : 1,

            ]);
        }

        $this->dispatchBrowserEvent('hide-update-modal', ['message' => '???? ?????????????? ??????????']);
    } //end update fucntion



    public function destroy()
    {
        Product::whereIn('id', $this->selectedRows)->each(function($q){
            if (File::exists(public_path('storage/products/'.$q->image_id))) {
                File::delete(public_path('storage/products/'.$q->image_id));
            }

            $q->delete();
        });
        $this->image_url_preview = '';

        $this->reset(['checked']);

        return $this->dispatchBrowserEvent('hide-delete-modal', ['message' => '???? ?????????? ??????????']);
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
                ->where('is_transfer', null)
                ->whereDate('created_at', '>=', $this->from)
                ->whereDate('created_at', '<=', $this->to)
                ->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('sell_price', 'like', '%' . $this->searchTerm . '%');
                })->latest()->paginate($this->paginateNumber);
        } else {
            return Product::where('is_direct', false)
            ->where('is_transfer', null)
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
