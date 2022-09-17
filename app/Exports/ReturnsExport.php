<?php

namespace App\Exports;

use App\Models\Returns;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ReturnsExport implements FromQuery, WithMapping,WithHeadings
{
    use Exportable;

    public $selectedRows;

    public function __construct($selectedRows)
    {
        $this->selectedRows = $selectedRows;
    }

    public function query()
    {
        return Returns::with('user')->whereIn('id', $this->selectedRows);
    }

    public function map($return): array
    {
        return [
            $return->id,
            $return->return,
            $return->user->name,
            $return->reason,
            $return->status,
        ];
    }

    public function headings(): array
    {
        return [
            'الرقم',
            'المرتجع',
            'المراجع',
            'السبب',
            'الحالة',
        ];
    }
}
