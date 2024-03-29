<?php

namespace App\Http\Controllers;

use App\Events\TestEvent;
use App\Http\Repositories\ProductRepository;
use App\Http\Requests\ProductSaveRequest;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    private $productModel;
    private $productRepository;

    public function __construct(Product $productModel, ProductRepository $productRepository)
    {
        $this->productModel = $productModel;
        $this->productRepository = $productRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $filter = [];

        // if (!empty($request->keyword)) {
        //     $filter[] = ['name', 'like', '%' . $request->keyword . '%'];
        // }
        // if ($request->status !== '' && !is_null($request->status)) {
        //     $filter[] = ['status', $request->status];
        // }

        // $sortBy = $request->input('sort-by') ?? 'id';
        // $sortType = $request->input('sort-type');

        // $sort = ['desc', 'asc'];
            
        // if (!empty($sortType) && in_array($sortType, $sort)) {
        //     $sortType = $sortType === 'desc' ? 'asc' : 'desc';
        // } else {
        //     $sortType = 'desc';
        // }

        // $products = Product::where($filter)->orderBy($sortBy, $sortType)->paginate(3);

        // $products = Product::with('category')->get();
        // $products = Product::all();
        $products = DB::table('product')->join('product_category', 'product.product_category_id', '=', 'product_category.id')
            ->select('product.*', 'product_category.name as product_category_name')
            ->get();

        return view('admin.pages.product.list', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $productCategory = ProductCategory::orderBy('id', 'DESC')->get();

        return view('admin.pages.product.create', compact('productCategory'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductSaveRequest $request)
    {
        $product = $this->productRepository->create($request);
  
        $message = 'that bai';
        if ($product->id) {
            // event(new ProductCreateSuccess());
            $message = 'thanh cong';
        }
        return redirect()->route('admin.product.list')->with('message', $message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = DB::table('product')->where('id', $id)->first();

        return view('admin.pages.product.detail', ['product' => $product]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = DB::table('product')->where('id', $id)->first();
        if ($product) {
            $oldImage = $product->image_url;

            $imageName = null;
            if ($request->image_url) {
                $imageName = uniqid() . '_' . $request->image_url->getClientOriginalName();
                $request->image_url->move(public_path('images'), $imageName);
                if (!is_null($oldImage) && file_exists("images/" . $oldImage)) {
                    unlink("images/" . $oldImage);
                }
            }

            if (!is_null($imageName)) {
                $check = DB::table('product')->where('id', $id)
                    ->update(['image_url' => $imageName]);
                $message = $check ? 'Thanh cong' : 'That bai';
                return redirect()->route('admin.product.list')->with('message', $message);
            }
        }
        return '404';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getSlug(Request $request)
    {
        // $slug = implode('-', explode(' ', $request->name));
        $slug = Str::slug($request->name);
        return response()->json(['slug' => $slug]);
    }

    public function storeImage(Request $request)
    {
        if ($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName . '_' . time() . '.' . $extension;
    
            $request->file('upload')->move(public_path('images'), $fileName);
    
            $url = asset('images/' . $fileName);
            return response()->json(['fileName' => $fileName, 'uploaded'=> 1, 'url' => $url]);
        }
    }
}
