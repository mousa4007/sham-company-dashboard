<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
            return $this->belongsTo(AppUser::class,'app_user_id');
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->isoFormat('Y-M-D h:m a');
    }
}
