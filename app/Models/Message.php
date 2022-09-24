<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['app_user_id', 'message'];

    public function user()
    {
        return $this->belongsTo(AppUser::class, 'app_user_id');
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->diffInHours();
    }

    // public function getCreatedAtAttribute($value)
    // {
    //     return Carbon::parse($value)->format('d-m-Y');
    // }
}
