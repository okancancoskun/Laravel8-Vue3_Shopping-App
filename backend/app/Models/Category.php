<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use app\Models\Product;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'name'
    ];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
