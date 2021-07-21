<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Cart;
use App\Models\Order;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    protected $guarded = [];

    protected $hidden = [
        'password',
    ];
    public function cart_id()
    {
        return $this->hasOne(Cart::class, 'id', 'cartId');
    }
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
