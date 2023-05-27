<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        // $productCategories = ProductCategory::orderBy('name', 'desc')->get()->filter(function ($productCategory) {
        //     return $productCategory->products->count() > 0;
        // })->slice(0, 10);

        // $productCategories = ProductCategory::limit(10)->get();

        $products = DB::table('product')
            ->join('product_category', 'product.product_category_id', '=', 'product_category.id')
            ->select('product.*', 'product_category.name as category_name')
            ->where('product_category.deleted_at', NULL)
            ->orderBy('product.id', 'desc')
            ->limit(8)
            ->get();

        $arrayProductCategory = $products->pluck('category_name')->unique();

        $latestProducts = Product::orderBy('id', 'desc')->limit(3)->get();
        $articles = Article::orderBy('id', 'desc')->limit(3)->get();

        return view('clients.pages.home', compact('products', 'arrayProductCategory', 'latestProducts', 'articles'));
    }

    public function getProductList(Request $request){
        $productCategories = ProductCategory::orderBy('name', 'desc')->get()->filter(function ($productCategory) {
            return $productCategory->products->count() > 0;
        })->slice(0, 10);
        

        $pipelines = [ 
            \App\Filters\ByCategory::class,
            \App\Filters\ByMinMax::class,
        ]; 
     
        //use Illuminate\Pipeline\Pipeline;
        $pipeline = app(Pipeline::class) 
            ->send(Product::query()) 
            ->through($pipelines) 
            ->thenReturn(); 
     
        $products = $pipeline->paginate(12); 

        // $products = Product::query();
        
        // $sort = $request->sort;
        // $sortBy = [];

        // switch($sort){
        //     case 0:
        //         $sortBy['field'] = 'created_at';
        //         $sortBy['sortBy'] = 'desc';
        //         break;
        //     case 1:
        //         $sortBy['field'] = 'price';
        //         $sortBy['sortBy'] = 'asc';
        //         break;
        //     case 2:
        //         $sortBy['field'] = 'price';
        //         $sortBy['sortBy'] = 'desc';
        //         break;
        //     default:
        //         $sortBy['field'] = 'created_at';
        //         $sortBy['sortBy'] = 'desc';
        // }

        // if(isset($request->category) && $request->category != 'all'){
        //     $products->where('product_category_id', $request->category);
        // }

        // if(isset($request->min) && isset($request->max)){
        //     $products->whereBetween('price', [$request->min, $request->max]);
        // }

        // $products = $products->orderBy($sortBy['field'],$sortBy['sortBy'])->paginate(12);
        
        return view('clients.pages.product_list', [
            'products' => $products, 
            'productCategories' => $productCategories,
            'min' => Product::min('price'),
            'max' => Product::max('price')
        ]);
    }

    public function getDetailBySlug($slug){
	    $product = Product::where('slug', $slug)->first();
        return view('clients.pages.product_detail', ['product' => $product]);
    }
}
