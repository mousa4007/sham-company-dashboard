<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function appUser()
    {
        return $this->belongsTo(AppUser::class, 'app_user_id');
    }

    public function getCreatedAtAttribute($value)
    {
        // return Carbon::parse($value)->diffForHumans();
        return Carbon::parse($value)->diffInHours();
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('m-d-Y');
    }
}
