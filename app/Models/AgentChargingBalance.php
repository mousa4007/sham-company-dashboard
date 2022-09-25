<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentChargingBalance extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function getCreatedAtAttribute($value) 
    {
        return Carbon::parse($value)->format('d-m-Y');
    }
}
