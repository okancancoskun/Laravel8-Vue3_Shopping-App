<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class CartItems extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function product_id()
    {
        return $this->hasOne(Product::class, 'id', 'productId');
    }
    protected $hidden = ['pivot'];
}
