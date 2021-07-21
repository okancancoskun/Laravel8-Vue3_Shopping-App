<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItems;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function create()
    {
        $newOrder = Order::create(["userId" => Auth::user()->id]);
        $cart =  Cart::with('cart_items.product_id')->find(Auth::user()->cartId);
        $cartItems = $cart->cart_items->toArray();
        foreach ($cartItems as $item) {
            $newOrder->order_items()->create([
                'productId' => $item['productId'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'order_id' => $newOrder->id
            ]);
        }
        $mapped = array_map(function ($item) {
            return $item['id'];
        }, $cartItems);
        CartItems::destroy($mapped);
        return response($newOrder, 201);
    }
    public function findOne($id)
    {
        return Order::with('order_items.product_id', 'user_id')->find($id);
    }
    public function findAll()
    {
        $order = Order::with('order_items.product_id', 'user_id')->get();
        return response($order, 200);
    }
    public function findOrdersByUserId($userId)
    {
        return Order::with('user_id', 'order_items.product_id')->where('userId', $userId)->get();
    }
}
