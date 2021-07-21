<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Models\Role;
use stdClass;

class AuthController extends Controller
{
    public function register()
    {
        $cart = Cart::create();
        $user = User::create([
            'name' => request('name'),
            'email' => request('email'),
            'password' => Hash::make(request('password')),
            'cartId' => $cart->id
        ]);
        $roleUser = Role::where('name', 'user')->firstOrFail();
        $user->assignRole($roleUser);
        return $user;
    }
    public function login(Request $request)
    {
        if (!Auth::attempt(
            $request->only('email', 'password')
        )) {
            return response([
                'message' => 'Invalid cred!'
            ], Response::HTTP_UNAUTHORIZED);
        }
        $user = Auth::user();
        $userObj = new stdClass();
        $userObj->id = $user->id;
        $userObj->email = $user->email;
        $userObj->name = $user->name;
        $userRoles = $user->roles->toArray();
        $mappedRoles = array_map(function ($item) {
            return $item['name'];
        }, $userRoles);
        $userObj->roles = $mappedRoles;
        $token = $user->createToken('token')->accessToken;
        return response(['user' => $userObj, "token" => $token], Response::HTTP_CREATED);
    }
    public function currentUser()
    {
        $user = User::with('cart_id.cart_items')->find(Auth::user()->id);
        return $user;
    }
}
