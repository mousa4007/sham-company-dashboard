<?php

namespace App\Http\Livewire;

use App\Models\Product;
use App\Models\Category;
use App\Models\DiscountException;
use App\Models\WebApiKey;
use Illuminate\Support\Facades\File;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ApiProducts extends Component
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
        $country_number,
        $service_code,
        $web_api,
        $smsActivateApi,
        $vakSmsApi,
        $secondLineApi,
        $arrangement,
        $from,
        $to,
        $checked = false,
        $selectedRows = [],
        $paginateNumber = 5;


    protected $rules = [
        'name' => 'required|string',
        'image_url' => 'required',
        'description' => 'required',
        'currency' => 'required',
        'category_id' => 'required|integer',
        'service_code' => 'required',
        'country_number' => 'required|integer',
        'web_api' => 'required',
    ];

    public function render()
    {
        return view(
            'livewire.apiProduct.api_products',
            [
                'products' => $this->direct_coding_product,

                'categories' => Category::latest()->get()
            ]
        );
    } //end render

    public function mount()
    {
        $this->web_api = 'sms-activate';
        $this->currency = 1;
        $this->paginateNumber = 5;
    } //end mount

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
            $image = $data['image_url']->store('/','products');
        }

        $product = Product::create([
            'name' => $data['name'],
            'image_url' => asset('storage/products/'.$image),
            'image_id' => $image,
            'category_id' => $data['category_id'],
            'arrangement' => $this->arrangement != '' ? $this->arrangement : 1,
            'description' => $this->description,
            'currency' => $this->currency,
            'is_direct' => true,
            'web_api' => $this->web_api,
            'country_number' => $this->country_number,
            'service_code' => $this->service_code
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
        $this->web_api = $product->web_api;
        $this->country_number = $product->country_number;
        $this->service_code = $product->service_code;
    } //end edit fucntion

    public function editApiKey()
    {
        $apis = Product::all();

        if (count($apis) > 0) {
            $this->smsActivateApi = $apis->first()->smsActivate_api_key;
            $this->vakSmsApi = $apis->first()->vakSms_api_key;
            $this->secondLineApi = $apis->first()->secondLine_api_key;
        } else {
            $this->smsActivateApi = '';
            $this->vakSmsApi = '';
            $this->secondLineApi = '';
        }
    }

    public function updateApiKey()
    {

        WebApiKey::query()->update([
            'smsActivate_api_key' => $this->smsActivateApi,
            'vakSms_api_key' => $this->vakSmsApi,
            'secondLine_api_key' => $this->secondLineApi,
        ]);

        Product::query()->update([
            'smsActivate_api_key' => $this->smsActivateApi,
            'vakSms_api_key' => $this->vakSmsApi,
            'secondLine_api_key' => $this->secondLineApi,
        ]);

        $this->dispatchBrowserEvent('hide-create-modal', ['message' => 'تم التعديل بنجاح']);
    }

    public function update()
    {
        $data = $this->validate([
            'name' => 'required|string',
            'category_id' => 'required|integer',
            'image_url' => 'nullable',
            'description' => 'required',
            'currency' => 'required',
        ]);

        $product = Product::find($this->ids);

        if ($data['image_url'] == null) {
            $product->update([
                'name' => $data['name'],
                'category_id' => $data['category_id'],
                'description' => $data['description'],
                'currency' => $data['currency'],
                'arrangement' => $this->arrangement != '' ? $this->arrangement : 1,
                'is_direct' => true,
                'web_api' => $this->web_api,
                'country_number' => $this->country_number,
                'service_code' => $this->service_code
            ]);
        } else {

            if (File::exists(public_path('storage/products/'.explode('/' ,$this->image_url_preview)[5]))) {
                File::delete(public_path('storage/products/'.explode('/' ,$this->image_url_preview)[5]));
            }

            if($data['image_url']){
                $image = $data['image_url']->store('/','products');
            }

            $product->update([
                'name' => $data['name'],
                'image_url' => asset('storage/products/'.$image),
                'image_id' => $image,
                'category_id' => $data['category_id'],
                'description' => $data['description'],
                'currency' => $data['currency'],
                'arrangement' => !is_null($this->arrangement) ? $this->arrangement : 1,
                'image_url' => asset('storage/products/'.$image),
                'is_direct' => true,
                'web_api' => $this->web_api,
                'country_number' => $this->country_number,
                'service_code' => $this->service_code
            ]);
        }

        $this->dispatchBrowserEvent('hide-update-modal', ['message' => 'تم التعديل بنجاح']);
    } //end update fucntion


    public function updatedChecked($value)
    {
        if ($value) {
            $this->selectedRows = $this->direct_coding_product->pluck('id');
        } else {
            $this->reset([]);
        }
    }

    public function getDirectCodingProductProperty()
    {
        if ($this->from) {
            return Product::where('is_direct', true)
                ->whereDate('created_at', '>=', $this->from)
                ->whereDate('created_at', '<=', $this->to)
                ->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('sell_price', 'like', '%' . $this->searchTerm . '%');
                })->latest()->paginate($this->paginateNumber);
        } else {
            return Product::where('is_direct', true)->where(function ($q) {
                $q->where('name', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('sell_price', 'like', '%' . $this->searchTerm . '%');
            })->latest()->paginate($this->paginateNumber);
        }
    }

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
}
