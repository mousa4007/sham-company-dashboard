<?php

namespace App\Http\Livewire;

use App\Exports\ReturnsExport;
use App\Exports\SalesExport;
use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Concerns\Exportable;



class Sales extends Component
{
    use WithPagination;
    use Exportable;
    protected $paginationTheme = 'bootstrap';

    public
        $searchTerm,
        $paginateNumber = 10,
        $from,
        $to,
        $categories,
        $product_id,
        $category_id,
        $selectedRows = [],
        $checked = false;


    public function render()
    {

        return view('livewire.sales.sales', [
            'products' => Product::paginate($this->paginateNumber),
            'categories' => $this->categories,
            'sales' => $this->sales
        ]);
    }

    public function mount()
    {
        $this->to = Carbon::today();
        $this->from = Carbon::today();
        $this->paginateNumber = 10;
        $this->categories = Category::all();
    }

    public function updatedChecked($value)
    {
        if ($value) {
            $this->selectedRows = $this->sales->pluck('id');
        } else {
            $this->reset(['checked', 'selectedRows']);
        }
    }

    public function getSalesProperty()
    {
        $query = Sale::query();

        $query->when($this->from, function ($q) {
            return $q->whereDate('created_at', '>=', $this->from)
                ->whereDate('created_at', '<=', $this->to);
        });

        $query->when($this->product_id, function ($q) {
            return $q->where('product_id', $this->product_id);
        });

        $query->when($this->category_id, function ($q) {
            // $categprories = Category::all();
            // return $q->where('product_id', $this->product_id);

            $product = Product::where('category_id', $this->category_id)->pluck('id');

            return $q->whereIn('product_id', $product);
        });

        return $query->groupBy('product_id')
            ->selectRaw('*, sum(price) as sum_price')
            ->selectRaw('count(*) as count_sell ')
            ->paginate($this->paginateNumber);

        // return $this->customized_sale;
    }
    public function export()
    {
        // return (new SalesExport($this->selectedRows))->download('المرتجعات.xls');
        // return Excel::download(new ReturnsExport, 'المرتجعات.xlsx');

        return (new SalesExport($this->from, $this->to,$this->product_id,$this->category_id))
        
        ->download('asdf.xls');
    }
}
