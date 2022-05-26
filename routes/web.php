<?php

use App\Layers\Web\Controller\Shop\ShopController;
use Illuminate\Support\Facades\Route;

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

Route::controller(ShopController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/shop/{shopItemId}', 'shopItemDetails');
    Route::get('/shop/{shopItemId}/billing/{purchaseCount}', 'getBillingInfo');
    Route::post('/shop/purchase', 'purchase');
});
