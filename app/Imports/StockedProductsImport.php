<?php

namespace App\Imports;


use App\Models\StockedProduct;
use Maatwebsite\Excel\Concerns\ToModel;

class StockedProductsImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // dd($row);
        return new StockedProduct([
            'product_id'  => $row[1],
            'product_item'  => $row[0]
        ]);
    }
}
