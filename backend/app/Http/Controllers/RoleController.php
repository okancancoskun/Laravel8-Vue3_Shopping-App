<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function create()
    {
        return Role::create([
            'name' => request('name')
        ]);
    }
    public function findOne($name)
    {
        return Role::where('name', $name)->firstOrFail();
    }
    public function findAll()
    {
        return Role::all();
    }
}
