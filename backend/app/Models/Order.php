<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrderItems;
use App\Models\User;

class Order extends Model
{
    use HasFactory;
    public function order_items()
    {
        return $this->hasMany(OrderItems::class);
    }
    public function user_id()
    {
        return $this->hasOne(User::class, 'id', 'userId');
    }
    protected $guarded = [];
}
