<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountException extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function discounts()
    {
        return $this->belongsTo(Discount::class,'discount_id');
    }
}
