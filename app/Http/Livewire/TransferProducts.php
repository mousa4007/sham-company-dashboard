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


class TransferProducts extends Component
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
        $paginateNumber,
        $hintMessage,
        $dataType;

    protected $rules = [
        'name' => 'required|string',
        'image_url' => 'required',
        'description' => 'required',
        'currency' => 'required',
        'hintMessage' => 'required',
        'dataType' => 'required',
        'category_id' => 'required|integer',
    ];

    public function render()
    {
        return view(
            'livewire.transfer_product.transfer-product',
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
        $this->dataType = 0;
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
            'is_transfer' => true,
            'only_number' => $data['dataType'] == 1 ? true : false,
            'hint_message' => $data['hintMessage']
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
        $this->hintMessage = $product->hint_message;
        $this->dataType = $product->only_number;
        $this->image_url = '';
    } //end edit fucntion

    public function update()
    {
        $data = $this->validate( [
            'name' => 'required|string',
            'image_url' => 'nullable',
            'description' => 'required',
            'currency' => 'required',
            'hintMessage' => 'required',
            'dataType' => 'required',
            'category_id' => 'required|integer',
        ]);

        $product = Product::find($this->ids);

        if ($data['image_url'] == null) {
            $product->update([
                'name' => $data['name'],
                'category_id' => $data['category_id'],
                'arrangement' => $this->arrangement != '' ? $this->arrangement : 1,
                'description' => $this->description,
                'currency' => $this->currency,
                'is_direct' => false,
                'is_transfer' => true,
                'only_number' => $data['dataType'] == 1 ? true : false,
                'hint_message' => $data['hintMessage']
            ]);
        } else {

            // if (File::exists(public_path('storage/products/'.explode('/' ,$this->image_url_preview)[5]))) {
            //     File::delete(public_path('storage/products/'.explode('/' ,$this->image_url_preview)[5]));
            // }

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
                'arrangement' => $this->arrangement != '' ? $this->arrangement : 1,
                'description' => $this->description,
                'currency' => $this->currency,
                'is_direct' => false,
                'is_transfer' => true,
                'only_number' => $data['dataType'] == 1 ? true : false,
                'hint_message' => $data['hintMessage']

            ]);
        }

        $this->dispatchBrowserEvent('hide-update-modal', ['message' => 'تم التعديل بنجاح']);
    } //end update fucntion

    public function destroy()
    {


        Product::whereIn('id', $this->selectedRows)->each(function($q){
            if (File::exists(public_path('storage/products/'.$q->image_id))) {
                File::delete(public_path('storage/products/'.$q->image_id));
            }

            $q->delete();
        });

        $this->image_url_preview='';
        $this->reset(['checked']);

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
                ->where('is_transfer', true)
                ->whereDate('created_at', '>=', $this->from)
                ->whereDate('created_at', '<=', $this->to)
                ->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('sell_price', 'like', '%' . $this->searchTerm . '%');
                })->latest()->paginate($this->paginateNumber);
        } else {
            return Product::where('is_direct', false)
                ->where('is_transfer', true)
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
