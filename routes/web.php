<?php

use App\Http\Controllers\ArticleCategoryController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\HomeController as ClientHomeController;
use App\Http\Controllers\Client\OrderController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\GoogleLoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductCategory;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\TestSendMailController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VnpayController;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [ClientHomeController::class, 'index'])->name('index');
Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/blog', [BlogController::class, 'index'])->name('blog');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::get('/product-list',[ClientHomeController::class, 'getProductList'])->name('product.list');
Route::get('/product/{slug}', [ClientHomeController::class, 'getDetailBySlug'])->name('product.detail');

Route::get('/vnpay/callback', [VnpayController::class, 'callbackVnpay'])->name('vnpay.callback');
Route::get('/user/order/{id}', [OrderController::class, 'getDetailOrderByCode'])->name('user.order.code');

include_once(__DIR__ . '/cart/web.php');

Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
Route::post('/checkout/place-order', [OrderController::class, 'placeOrder'])->name('checkout.place-order');

Route::get('/dangnhap', [UserController::class, 'giaodiendangnhap'])->name('giaodiendangnhap');
Route::post('/dangnhap', [UserController::class, 'dangnhap'])->name('dangnhap');
Route::post('/dangxuat', [UserController::class, 'dangxuat'])->name('dangxuat');

Route::prefix('admin')->middleware('is.admin')->group(function () {

    // Route::resource('product', ProductController::class);

    Route::get('/product/list', [ProductController::class, 'index'])->name('admin.product.list');

    Route::post('/product/save', [ProductController::class, 'store'])->name('admin.product.save');

    Route::get('/product/detail/{id}', [ProductController::class, 'edit'])->name('admin.product.detail');

    Route::post('/product/edit/{id}', [ProductController::class, 'update'])->name('admin.product.edit');

    Route::post('/product/image-upload', [ProductController::class, 'storeImage'])->name('admin.product.image.upload');

    Route::get('/', function () {
        return view('admin.pages.user');
    })->name('admin.index');

    Route::get('/user', [UserController::class, 'index'])->name('admin.user');

    Route::get('/user/create', function () {
        return view('admin.pages.user.create');
    })->name('admin.user.create');

    Route::get('/user/{id}', [UserController::class, 'show'])->name('admin.user.detail');

    Route::post('/user/update', [UserController::class, 'update'])->name('admin.user.update');

    Route::get('/user/delete/{id}', [UserController::class, 'destroy'])->name('admin.user.delete');

    Route::get('/product/create', [ProductController::class, 'create'])->name('admin.product.create');

    Route::get('/blog', function () {
        return view('admin.pages.blog');
    })->name('admin.blog');

    Route::post('/user/save', [UserController::class, 'store'])->name('admin.user.save');

    Route::resource('product-category', ProductCategoryController::class);
    Route::get('product-category/create', [ProductCategoryController::class, 'create'])->name('product-category.create');

    Route::resource('article', ArticleController::class);
    Route::post('/article-get-slug', [ArticleController::class, 'getSlug'])->name('article.get.slug');

    Route::resource('article-category', ArticleCategoryController::class);
    Route::post('article-category/{article_category}/restore', [ArticleCategoryController::class, 'restore'])->name('article-category.restore');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/write/generate', [ArticleController::class, 'generate'])->name('write-generate');

Route::post('/product-get-slug', [ProductController::class, 'getSlug'])->name('product.get.slug');


Route::get('/test-send-mail', [TestSendMailController::class, 'sendMail']);

Route::get('/auth/google/redirect', [GoogleLoginController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleLoginController::class, 'callback']);

