<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function findAll()
    {
        return Product::with('category')->paginate(3);
    }
    public function findOne($id = null)
    {
        return Product::with('category')->find($id);
    }
    public function findByCategory($category)
    {
        $cat = Category::where('name', $category)->first();
        return Product::where('categoryId', $cat->id)->get();
    }
    public function create(Request $request)
    {
        $fields = $request->validate([
            'name' => 'string',
            'detail' => 'string',
            'price' => 'integer',
            'categoryId' => 'integer',
        ]);
        return Product::create([
            'name' => $fields['name'],
            'detail' => $fields['detail'],
            'price' => $fields['price'],
            'categoryId' => $fields['categoryId']
        ]);
    }
    public function updateOne(Request $request, Product $product)
    {
        $product->update($request->all());
        return response()->json($product, 200);
    }
    public function deleteOne(Product $product)
    {
        $product->delete();
        return response()->json(null, 204);
    }
    public function uploadFile(Request $request, $id)
    {
        $uploadedFileUrl = Cloudinary::upload($request->file('file')->getRealPath())->getSecurePath();
        return $uploadedFileUrl;
    }
}
