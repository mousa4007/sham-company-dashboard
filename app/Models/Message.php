<?php

namespace App\Models;

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
}
