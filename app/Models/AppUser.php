<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laratrust\Traits\LaratrustUserTrait;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;


class AppUser extends Authenticatable implements MustVerifyEmail
{
    use LaratrustUserTrait;
    use HasApiTokens, HasFactory, Notifiable;

    protected $guarded = [];

    public function orders()
    {
        return $this->hasMany(Order::class, 'app_user_id')->latest();
    }

    public function fovorite()
    {
        return $this->hasMany(Favorite::class, 'app_user_id');
    }

    public function userProductFavorite()
    {
        return $this->belongsToMany(Product::class, 'product_user_favorite', 'app_user_id', 'product_id')->withPivot('product_id');
    }

    public function agent()
    {
        return $this->hasMany(Agent::class, 'app_user_id', 'id')->latest();
    }


    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->diffForHumans();
    }

    public function returns()
    {
        return $this->hasMany(Returns::class, 'app_user_id');
    }

    public function notifcations()
    {
        return $this->hasMany(Notification::class, 'app_user_id');
    }
}
