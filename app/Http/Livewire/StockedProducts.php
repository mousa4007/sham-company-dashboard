<?php

namespace App\Http\Livewire;

use App\Imports\StockedProductsImport;
use App\Models\Category;
use App\Models\Product;
use App\Models\StockedProduct;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class StockedProducts extends Component
{
    use WithPagination;
    use WithFileUploads;
    protected $paginationTheme = 'bootstrap';

    protected $listeners = ['sectionRefresh' => '$refresh'];

    public $product_item,
        $categoryId,
        $products = [],
        $product_id,
        $ids,
        $parentCategoryId,
        $searchTerm,
        $product_item_id,
        $excel_file,
        $paginateNumber = 10,
        $from, $to,
        $checked =  false,
        $selectedRows = [],
        $checkedShow,
        $selectedRowsShow = [],
        $lineNumber, $data = [], $count,
        $class, $style, $error;

    public function mount()
    {
        $this->products = Product::where('category_id', $this->parentCategoryId)->get();
        $this->paginateNumber = 5;
        $this->count = count(StockedProduct::all());
    }

    public function render()
    {
        return view(
            'livewire.stocked_products.stocked_products',
            [
                'stockedProducts' => $this->stockedProduct,
                'categories' => Category::all(),
                'productList' => Product::all(),
                'products' =>$this->products
            ],
        );
    }
    public function updatedCategoryId()
    {
        if (!is_null($this->categoryId)) {
            $this->products = Product::where('is_direct',false)->where('is_transfer',null)->where('category_id', $this->categoryId)->get();
        }
    }

    public function updatedParentCategoryId()
    {
        if (!is_null($this->parentCategoryId)) {
            $this->products = Product::where('category_id', $this->parentCategoryId)->get();
            $this->product_id = Product::where('category_id', $this->parentCategoryId)->first()->id;
        }
    }

    public function store()
    {
        $data = $this->validate([
            'product_item' => 'required',
            'categoryId' => 'required',
            'product_id' => 'required',
            'lineNumber' => 'required|numeric|max:10'
        ]);

        // $array = explode('-', $this->product_item);

        $file = 'file.txt';

        file_put_contents($file, $data);

        $chunks = array_chunk(file($file), $this->lineNumber);

        for ($i = 0; $i < count($chunks); $i++) {
            StockedProduct::create([
                'product_id' => $data['product_id'],
                'product_item' => implode(' ', $chunks[$i]),
            ]);
        }

        $this->dispatchBrowserEvent('hide-create-modal', ['message' => 'تمت الإضافة بنجاح']);
    } //end store function

    public function resetData()
    {
        $this->product_item = '';
        $this->product_id = '';
        $this->categoryId = '';
    }

    public function edit(StockedProduct $product_item)
    {
        $this->product_item_id = $product_item->id;
        $this->product_item = $product_item->product_item;
        $this->product_id = $product_item->product_id;
        $this->parentCategoryId = Product::find($product_item->product_id)->category->id;

        $this->products = Category::find($this->parentCategoryId)->products;
    }

    public function update()
    {
        $product_item = StockedProduct::find($this->product_item_id);

        $data = $this->validate([
            'product_item' => 'required',
            // 'categoryId' => 'required',
            'product_id' => 'required',
        ]);

        $product_item->update([
            'product_id' => $data['product_id'],
            'product_item' => $data['product_item'],
        ]);

        $this->dispatchBrowserEvent('hide-update-modal', ['message' => 'تم التعديل بنجاح']);
    } //end store function

    public function destroy()
    {
        StockedProduct::whereIn('created_at', $this->selectedRows)->delete();

        $this->reset(['checked', 'selectedRows']);

        return $this->dispatchBrowserEvent('hide-delete-modal', ['message' => 'تم الحذف بنجاح']);
    }

    public function destroyShow()
    {
        StockedProduct::whereIn('id', $this->selectedRowsShow)->delete();

        $this->reset(['checkedShow', 'selectedRowsShow']);

        $this->emitUp('sectionRefresh');
        $this->class = "show";
        $this->style = "display: bl
        ock;";

        return $this->dispatchBrowserEvent('hide-delete-modal', ['message' => 'تم الحذف بنجاح']);
    }

    public function updatedCheckedShow($value)
    {
        if ($value) {
            $arr = [];
            foreach ($this->data as $key => $value) {
                array_push($arr, $value['id']);
            }
            $this->selectedRowsShow = $arr;
        } else {
            $this->reset(['selectedRowsShow', 'checkedShow']);
        }
    }

    public function updatedChecked($value)
    {
        $array  = (array) null;
        if ($value) {
            foreach ($this->stockedProduct as $key => $value) {

                array_push($array, $key);
            }
        } else {
            $this->reset(['selectedRows', 'checked']);
        }

        $this->selectedRows = $array;
    }

    // public function refresh()
    // {
    //     return;
    // }

    public function import()
    {
        // dd($this->excel_file);
        Excel::import(new StockedProductsImport, $this->excel_file);

        $this->excel_file = '';

        $this->dispatchBrowserEvent('hide-create-modal', ['message' => 'تمت الإضافة بنجاح']);
    }

    public function getStockedProductProperty()
    {
        return StockedProduct::query()
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($item) {
                return $item->created_at->format('Y-m-d H:i:s');
            })->paginate($this->paginateNumber);
    }

    public function disable()
    {
        StockedProduct::whereIn('created_at', $this->selectedRows)->update([
            'status' => 'disabled'
        ]);

        $this->reset(['selectedRowsShow', 'checkedShow']);
    }

    public function disableShow()
    {
        StockedProduct::whereIn('id', $this->selectedRowsShow)->update([
            'status' => 'disabled'
        ]);

        $this->dispatchBrowserEvent('hide-update-modal', ['message' => 'تم التعديل بنجاح']);
    }

    public function activate()
    {
        StockedProduct::whereIn('created_at', $this->selectedRows)->update([
            'status' => 'active'
        ]);
    }

    public function activateShow()
    {
        StockedProduct::whereIn('id', $this->selectedRowsShow)->update([
            'status' => 'active'
        ]);

        $this->dispatchBrowserEvent('hide-update-modal', ['message' => 'تم التعديل بنجاح']);
    }

    public function setData($stocked)
    {
        $this->data = $stocked;
    }
}
