<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\CartItems;

class Cart extends Model
{
    use HasFactory;
    /* public function user()
    {
        return $this->belongsTo(User::class);
    } */
    protected $hidden = ['pivot'];
    public function cart_items()
    {
        return $this->belongsToMany(CartItems::class, 'cart_cart_items', 'cart_id', 'cart_items_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    protected $guarded = [];
}
