<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Agent extends Model
{

    use HasFactory;

    protected $fillable = [
        "id",
        "name",
        "email",
        "password",
        "balance",
        "app_user_id",
        "created_at",
        "updated_at",
        "phone",
        "address"
    ];

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->diffInHours();
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->diffInHours();
    }

    public function user()
    {
        return $this->belongsTo(AppUser::class,'app_user_id');
    }


}
