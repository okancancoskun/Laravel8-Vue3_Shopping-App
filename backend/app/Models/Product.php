<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\CartItems;
use App\Models\OrderItems;

class Product extends Model
{
    use HasFactory;
    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'categoryId');
    }
    public function cart()
    {
        return $this->belongsTo(CartItems::class);
    }
    public function order()
    {
        return $this->belongsTo(OrderItems::class);
    }
    protected $hidden = ['categoryId'];
    protected $guarded = [];
}
