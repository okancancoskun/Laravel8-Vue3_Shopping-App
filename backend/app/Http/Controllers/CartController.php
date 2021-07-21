<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItems;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use SebastianBergmann\Environment\Console;

class CartController extends Controller
{
    public function create()
    {
        return Cart::create([
            'name' => request('name')
        ]);
    }

    public function addItem()
    {
        $prd = Product::find(request('productId'));
        $cart = Cart::with('cart_items.product_id')->find(Auth::user()->cartId);
        $cartItems = $cart->cart_items;
        $toArray = $cartItems->toArray();
        if (count($toArray) > 0) {
            $mapped = array_map(function ($item) {
                return $item['productId'];
            }, $toArray);
            $index = array_search(request('productId'), $mapped);
            if (in_array(request('productId'), $mapped)) {
                $cartItems[$index]->quantity = $cartItems[$index]->quantity + 1;
                $cartItems[$index]->price = $cartItems[$index]->price + $prd->price;
                $cartItems[$index]->save();
                $cart->load('cart_items.product_id');
                return response($cartItems[$index], 201);
            } else {
                $createdItem = CartItems::create([
                    'productId' => request('productId'),
                    'quantity' => 1,
                    'price' => $prd->price
                ]);
                $cart->cart_items()->attach($createdItem);
                $cart->load('cart_items.product_id');
                $result = CartItems::with('product_id')->find($createdItem->id);
                 return response($result, 201);
            }
        } else {
            $createdItem = CartItems::create([
                'productId' => request('productId'),
                'quantity' => 1,
                'price' => $prd->price
            ]);
            $cart->cart_items()->attach($createdItem);
            $cart->load('cart_items.product_id');
            $result = CartItems::with('product_id')->find($createdItem->id);
            return response($result, 201);
        }
    }
    public function getCartByUserId($userId)
    {
        $cart =  Cart::with('cart_items.product_id')->find(User::find($userId)->cartId);
        return response($cart, 200);
    }
    public function clearCart()
    {
        $cart = Cart::with('cart_items.product_id')->find(Auth::user()->cartId);
        $cartItems = $cart->cart_items->toArray();
        $mapped = array_map(function ($item) {
            return $item['id'];
        }, $cartItems);
        CartItems::destroy($mapped);
        /*  $cart->cart_items()->detach($mapped); */
        $cart->load('cart_items');
        return response($cart, 200);
    }
    public function removeItem()
    {
        $cart = Cart::with('cart_items.product_id')->find(Auth::user()->cartId);
        $cartItems = $cart->cart_items->toArray();
        $mapped = array_map(function ($item) {
            return $item['productId'];
        }, $cartItems);
        $index = array_search(request('productId'), $mapped);
        $removedItemId = $cartItems[$index]['id'];
        /* $cart->cart_items()->detach($removedItemId); */
        CartItems::destroy($removedItemId);
        $cart->load('cart_items');
        return response($cart, 201);
    }
    public function decreaseItemByOne()
    {
        $cart = Cart::with('cart_items.product_id')->find(Auth::user()->cartId);
        $cartItems = $cart->cart_items->toArray();
        $mapped = array_map(function ($item) {
            return $item['productId'];
        }, $cartItems);
        $index = array_search(request('productId'), $mapped);
        if ($cartItems[$index]['quantity'] > 1) {
            $cart->cart_items[$index]->quantity--;
            $cart->cart_items[$index]->save();
            $cart->load('cart_items');
            return response($cart, 201);
        } else {
            return response()->json([
                'message' => 'Bad Request'
            ], 400);
        }
    }
}
