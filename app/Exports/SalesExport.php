<?php

namespace App\Exports;

use App\Models\Product;
use App\Models\Sale;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithBackgroundColor;
use Maatwebsite\Excel\Concerns\WithStyles;

class SalesExport implements
    FromQuery,
    WithMapping,
    WithHeadings,
    ShouldAutoSize,
    WithStyles
{
    use Exportable;
    public $from, $to, $product_id, $category_id;



    public function styles(Worksheet $sheet)
    {
        // $sheet->getStyle('B2')->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_DARKGREEN));

        return [
            // Style the first row as bold text.
            'A1'    => ['font' => ['bold' => true]],
            'B1'    => ['font' => ['bold' => true]],
            'C1'    => ['font' => ['bold' => true]],
            'D1'    => ['font' => ['bold' => true]],

            // Styling a specific cell by coordinate.
            // 'B2' => ['font' => ['italic' => true]],

            // Styling an entire column.
            // 'C'  => ['font' => ['size' => 16]],

           
        ];
    }

    public function __construct($from, $to, $product_id, $category_id)
    {
        $this->from = $from;
        $this->to = $to;
        $this->product_id = $product_id;
        $this->category_id = $category_id;
    }


    public function defaultStyles(Style $defaultStyle)
    {
        // Configure the default styles
        return $defaultStyle->getFill()->setFillType(Fill::FILL_SOLID);

        // Or return the styles array
        return [
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['argb' => Color::COLOR_BLUE],
            ],
        ];
    }

    // public function styles(Worksheet $sheet)
    // {
    //     return [
    //         // Style the first row as bold text.
    //         1    => ['font' => ['bold' => true]],

    //         // Styling a specific cell by coordinate.
    //         'B2' => ['font' => ['italic' => true]],

    //         // Styling an entire column.
    //         'C'  => ['font' => ['size' => 16]],
    //     ];
    // }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $cellRange = 'A1:W1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
            },
        ];
    }


    public function query()
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

            $product = Product::where('category_id', $this->category_id)->pluck('id');
            return $q->whereIn('product_id', $product);
        });

        return $query->groupBy('product_id')
        ->orderby('product_id','desc')
            ->selectRaw('*, sum(price) as sum_price')
            ->selectRaw('count(*) as count_sell ');
    }

    public function map($sale): array
    {
        return [
            $sale->products->name,
            Product::find( $sale->products->id)->category->name,
            $sale->sum_price,
            $sale->count_sell
        ];
    }

    public function headings(): array
    {
        return [
            'المنتج',
            'القسم',
            'المقبوضات',
            'المبيعات'
        ];
    }
}
