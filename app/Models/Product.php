<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function stockedProduct()
    {
        return $this->hasMany(StockedProduct::class, 'product_id');
    }

    public function return()
    {
        return $this->hasMany(Returns::class, 'product_id');
    }

    public function api()
    {
        return $this->hasOne(WebApiKey::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class,'product_id');
    }
}
