<?php
namespace App\Http\Repositories;

use App\Http\Requests\ProductSaveRequest;
use App\Models\Product;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;

class ProductRepository{
    public function create(Request|ProductSaveRequest $request){
        $imageName = null;
        if ($request->image_url) {
            $imageName = uniqid() . '_' . $request->image_url->getClientOriginalName();
            $request->image_url->move(public_path('images'), $imageName);
        }

        $slug = Str::slug($request->name);

        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'discount_price' => $request->discount_price,
            'description' => $request->description,
            'status' => $request->status,
            'image_url' => $imageName,
            'product_category_id' => $request->product_category_id,
            'slug' => $slug
        ]);

        return $product;
    }

    public function getDetail(){

    }

    public function update(){

    }

    public function getList(){
        $products = Product::all();
        return $products;
    }
    public function delete(){
        
    }

    public function forceDelete(){
        
    }
    
}
?>