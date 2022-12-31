<?php

namespace App\Exports;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrdersExport implements WithHeadings,WithMapping,FromQuery,ShouldAutoSize,WithStyles,WithEvents
{
    use Exportable;

    public $from,$to, $product_id,$category_id,$app_user_id,$searchTerm,$userIdSearchTerm;

    public function __construct($from,$to, $product_id,$category_id,$app_user_id,$searchTerm,$userIdSearchTerm)
    {
        $this->from = $from;
        $this->to = $to;
        $this->product_id = $product_id;    
        $this->category_id = $category_id;
        $this->app_user_id = $app_user_id;
        $this->searchTerm = $searchTerm;
        $this->userIdSearchTerm = $userIdSearchTerm;
    }

       /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $event->sheet->getDelegate()->setRightToLeft(true);
            },
        ];
    }

    

    public function styles(Worksheet $sheet)
    {
        // $sheet->getStyle('B2')->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_DARKGREEN));

        return [
            // Style the first row as bold text.
            'A1'    => ['font' => ['bold' => true]],
            'B1'    => ['font' => ['bold' => true]],
            'C1'    => ['font' => ['bold' => true]],
            'D1'    => ['font' => ['bold' => true]],

        ];
    }

    public function query()
    {
        $query = Order::query();

        $query->when($this->from,function($q){
            return $q->where('created_at','>=',$this->from)
            ->where('created_at','<=',$this->to);
        });

        $query->when($this->product_id,function($q){
            return $q->where('product_id',$this->product_id);
        });

        $query->when($this->app_user_id,function($q){
            return $q->where('app_user_id',$this->app_user_id);
        });

        $query->when($this->category_id, function ($q) {

            $ids = Category::find($this->category_id)->products->pluck('id');

            return $q->whereIn('product_id', $ids)->get();
        });

        $query->when($this->searchTerm,function($q){
            return $q->where('id',$this->searchTerm);
        });

        $query->when($this->userIdSearchTerm,function($q){
            return $q->where('app_user_id',$this->userIdSearchTerm);
        });

        return $query->groupBy('product_id')
        ->orderby('product_id','asc')
            ->selectRaw('*, sum(price) as sum_price,sum(profit) as sum_profit')
            ->selectRaw('count(*) as count_sell ');              
    }

    public function map($order):array
    {
        return [
            Product::find($order->product_id)->name,
            Product::find($order->product_id)->category->name,
            $order->sum_price,
            $order->count_sell,
            $order->sum_profit,
            '=SUM(E:E)',
            '=SUM(D:D)'
        ];
    }

    public function headings():array
    {   
        return [
            'المنتج',
            'القسم',
            'المقبوضات',
            'المبيعات',
            'مربح الوكلاء',
            'مجموع مربح الوكلاء',
            'مجموع المبيعات',
        ];
    }

}
