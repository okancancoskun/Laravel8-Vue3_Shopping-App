<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function assignRole()
    {
        $user = User::find(request('userId'));
        $role = Role::findById(request('roleId'));
        $user->assignRole($role);
        return response([$user], Response::HTTP_OK);
        return $role;
    }
    public function findAll()
    {
    }
}
