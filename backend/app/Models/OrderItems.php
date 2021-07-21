<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\Order;

class OrderItems extends Model
{
    use HasFactory;
    public function product_id()
    {
        return $this->hasOne(Product::class, 'id', 'productId');
    }
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
    protected $guarded = [];
}
