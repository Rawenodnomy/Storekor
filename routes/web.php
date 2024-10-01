<?php

use App\Http\Controllers\BasketController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProductsController;
use App\Http\Controllers\Admin\TypesController;
use App\Http\Controllers\Admin\OrdersController;
use App\Http\Controllers\Admin\GroupController;
use App\Http\Controllers\Admin\InfoController;
use App\Http\Controllers\Admin\VersionsController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CosmeticController;
use App\Http\Controllers\Admin\VideoController;
use App\Http\Controllers\Admin\FoodController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\StocksController;

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/', [MainController::class, 'index']);
Route::get('/getProductsByGroup/{id}', 'App\Http\Controllers\MainController@getProductsByGroup');
Route::get('/showProduct/{id}', 'App\Http\Controllers\MainController@showProduct');
Route::get('/getInfo/{id}', 'App\Http\Controllers\MainController@getInfo');
Route::get('/getProductsByType/{id}', 'App\Http\Controllers\MainController@getProductsByType');
Route::get('/getgGroups', [MainController::class, 'getGroups']);
Route::get('/catalog', [MainController::class, 'getCatalog']);
Route::post('/deleteComment', [MainController::class, 'deleteComment']);
Route::post('/createComment', [MainController::class, 'createComment']);
Route::get('/editProfile/', 'App\Http\Controllers\MainController@editProfile');
Route::post('/saveEditProfile', [MainController::class, 'saveEditProfile']);
Route::get('/getContact/', 'App\Http\Controllers\MainController@getContact');
Route::post('/saveContacts', [MainController::class, 'saveContacts']);
Route::post('/search', [MainController::class, 'search']);


Route::get('/getProductsByBrand/{id}', 'App\Http\Controllers\MainController@getProductsByBrand');
Route::get('/getProductsByTexture/{id}', 'App\Http\Controllers\MainController@getProductsByTexture');
Route::get('/catalogCosmetc', [MainController::class, 'getCosmetics']);
Route::post('/filtrCosmetic', [MainController::class, 'filtrCosmetic']);
Route::get('/getFoodByCategory/{id}', 'App\Http\Controllers\MainController@getFoodByCategory');
Route::get('/catalogFood', 'App\Http\Controllers\MainController@catalogFood');
Route::get('/getFavorite', [MainController::class, 'getFavorite']);
Route::get('/delfav/{id}', 'App\Http\Controllers\MainController@delfav');
Route::get('/favorite/{id}', 'App\Http\Controllers\MainController@favorite');
Route::get('/getNews', 'App\Http\Controllers\MainController@getNews');
Route::get('/getNew/{id}', 'App\Http\Controllers\MainController@getNew');
Route::get('/getBrands', 'App\Http\Controllers\MainController@getBrands');

Route::get('/sitemap.xml', function () {
    $file = public_path('sitemap.xml');
    return response()->file($file);
});


Route::middleware('auth:sanctum')->group(function(){
    Route::post('/basketadd/{id}', [BasketController::class, 'add']);
    Route::post('/basketminus/{id}', [BasketController::class, 'minus']);
    Route::post('/basketremove/{id}', [BasketController::class, 'remove']);
    Route::get('/basket', [BasketController::class, 'getUserBasket']);
    Route::post('/addorder', [BasketController::class, 'addOrder']);
});

Route::post('/getcatalog', [MainController::class, 'getAsyncCatalog']);
Route::get('/getsearch', [MainController::class, 'getsearch']);
Route::get('/getOrdersByUser/', 'App\Http\Controllers\MainController@getOrdersByUser');
Route::get('/getOrdersProduct/{id}', 'App\Http\Controllers\MainController@getOrdersProduct');
Route::get('/countBasket', [MainController::class, 'countBasket']);
Route::prefix('admin')->group(function(){
    Route::get('/home', [HomeController::class, 'adminHome'])->name('admin.home')->middleware('is_admin');
    Route::resource('products', ProductsController::class);
    Route::resource('types', TypesController::class);
    Route::resource('orders', OrdersController::class);
    Route::resource('groups', GroupController::class);
    Route::resource('infos', InfoController::class);
    Route::resource('versions', VersionsController::class);
    Route::resource('comments', CommentController::class);
    Route::resource('users', UserController::class);
    Route::resource('cosmetics', CosmeticController::class);
    Route::resource('video', VideoController::class);
    Route::resource('foods', FoodController::class);
    Route::resource('news', NewsController::class);
    Route::resource('stocks', StocksController::class);
});

