<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function findAll()
    {
        return Category::all();
    }

    public function create()
    {
        return Category::create([
            'name' => request('name')
        ]);
    }
    public function updateOne(Category $id)
    {
        return $id->update([
            'name' => request('name')
        ]);
    }
    public function findOne($id)
    {
        return Category::find($id);
    }
    public function deleteOne(Category $id)
    {
        $success = $id->delete();
        return [
            'success' => $success
        ];
    }
}
