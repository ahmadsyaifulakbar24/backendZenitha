<?php

use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\Auth\LogoutController;
use App\Http\Controllers\API\Auth\RegisterController;
use App\Http\Controllers\API\Auth\UserController;
use App\Http\Controllers\API\Cart\CreateCartController;
use App\Http\Controllers\API\Cart\DeleteCartController;
use App\Http\Controllers\API\Cart\GetCartController;
use App\Http\Controllers\API\Cart\UpdateCartController;
use App\Http\Controllers\API\Discount\DiscountController;
use App\Http\Controllers\API\Discount\ShippingDiscountController;
use App\Http\Controllers\API\MasterData\VariantController;
use App\Http\Controllers\API\MasterData\CategoryController;
use App\Http\Controllers\API\MasterData\SizePackController;
use App\Http\Controllers\API\MasterData\SubCategoryController;
use App\Http\Controllers\API\MasterData\VariantOptionController;
use App\Http\Controllers\API\Product\CreateProductController;
use App\Http\Controllers\API\product\DeleteProductController;
use App\Http\Controllers\API\product\GetProductController;
use App\Http\Controllers\API\Product\ProductVariantOptionController;
use App\Http\Controllers\API\Product\UpdateProudctController;
use App\Http\Controllers\API\Region\RegionController;
use App\Http\Controllers\APi\Role\RoleController;
use App\Http\Controllers\API\Transaction\TransactionController;
use App\Http\Controllers\API\User\CreateUserController;
use App\Http\Controllers\API\User\DeleteUserController;
use App\Http\Controllers\API\User\GetUserController;
use App\Http\Controllers\API\User\UpdateUserController;
use App\Http\Controllers\API\User\UserAddressController;
use App\Http\Controllers\API\WebSetting\BannerController;
use App\Http\Controllers\API\WebSetting\OtherSetting\FooterBannerController;
use App\Http\Controllers\API\WebSetting\OtherSetting\SecondBannerController;
use App\Http\Controllers\API\WebSetting\SettingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// without auth
    Route::prefix('auth')->group( function() {
        Route::post('/login', LoginController::class);
        Route::post('/register', RegisterController::class);
    });

    Route::prefix('region')->group(function() {
        Route::get('/province/{province:id?}', [RegionController::class, 'province']);
        Route::get('/city/{city:id?}', [RegionController::class, 'city']);
        Route::get('/district/{district:id?}', [RegionController::class, 'district']);
    });

    Route::prefix('category')->group(function() {
        Route::get('/fetch', [CategoryController::class, 'fetch']);
        Route::get('/show/{category:id}', [CategoryController::class, 'show']);
        Route::get('/slug/{category:category_slug}', [CategoryController::class, 'get_by_slug']);
    });

    Route::prefix('sub_category')->group(function() {
        Route::get('/fetch', [SubCategoryController::class, 'fetch']);
        Route::get('/show/{sub_category:id}', [SubCategoryController::class, 'show']);
        Route::get('/slug/{sub_category:sub_category_slug}', [SubCategoryController::class, 'get_by_slug']);
    });

    Route::prefix('product')->group(function() {
        Route::get('/fetch', [GetProductController::class, 'fetch']);
        Route::get('show/{product:id}', [GetProductController::class, 'show']);
        Route::get('product_combination', [GetProductController::class, 'product_combination']);
        Route::get('product_combination_by_slug/{product_combination:product_slug}', [GetProductController::class, 'product_combination_slug']);
        Route::get('variant_option', [ProductVariantOptionController::class, 'get_product_variant_option']);
    });

    Route::prefix('banner')->group(function() {
        Route::get('/fetch', [BannerController::class, 'get']);
        Route::get('/show/{banner:id}', [BannerController::class, 'show']);
    });

    Route::prefix('setting')->group(function () {
        // main setting
        Route::get('/', [SettingController::class, 'get']);

        //second banner
        Route::get('/second_banner/fetch', [SecondBannerController::class, 'get']);
        Route::get('/second_banner/show/{second_banner:id}', [SecondBannerController::class, 'show']);

        //fotter banner
        Route::get('/footer_banner/fetch', [FooterBannerController::class, 'get']);
    });

    Route::prefix('discount')->group(function() {
        Route::get('/fetch', [DiscountController::class, 'get']);
        Route::get('/show/{discount:id}', [DiscountController::class, 'show']);
    });

    Route::prefix('shipping_discount')->group(function() {
        Route::get('/show', [ShippingDiscountController::class, 'show']);
    });

    Route::prefix('user')->group(function() {
        Route::post('/create_customer', [CreateUserController::class, 'customer']);
    });
// end without auth


// with auth
    Route::middleware(['auth:api'])->group(function () {
        Route::prefix('auth')->group( function() {
            Route::get('/user', UserController::class);
            Route::post('/logout', LogoutController::class);
        });

        Route::prefix('category')->group(function() {
            Route::post('/create', [CategoryController::class, 'create']);
            Route::put('/update/{category:id}', [CategoryController::class, 'update']);
            Route::delete('/delete/{category:id}', [CategoryController::class, 'delete']);
        });

        Route::prefix('sub_category')->group(function() {
            Route::post('/create', [SubCategoryController::class, 'create']);
            Route::put('/update/{sub_category:id}', [SubCategoryController::class, 'update']);
            Route::delete('/delete/{sub_category:id}', [SubCategoryController::class, 'delete']);
        });

        Route::prefix('variant')->group(function() {
            Route::post('/create', [VariantController::class, 'create']);
            Route::get('/fetch', [VariantController::class, 'fetch']);
            Route::get('/show/{variant:id}', [VariantController::class, 'show']);
            Route::put('/update/{variant:id}', [VariantController::class, 'update']);
            Route::delete('/delete/{variant:id}', [VariantController::class, 'delete']);
        });

        Route::prefix('variant_option')->group(function() {
            Route::post('/create', [VariantOptionController::class, 'create']);
            Route::get('/fetch', [VariantOptionController::class, 'fetch']);
            Route::get('/show/{variant_option:id}', [VariantOptionController::class, 'show']);
            Route::put('/update/{variant_option:id}', [VariantOptionController::class, 'update']);
        });

        Route::prefix('size_pack')->group(function() {
            Route::get('/fetch', [SizePackController::class, 'get']);
            Route::post('/create', [SizePackController::class, 'create']);
            Route::get('/show/{size_pack:id}', [SizePackController::class, 'show']);
            Route::put('/update/{size_pack:id}', [SizePackController::class, 'update']);
            Route::delete('/delete/{size_pack:id}', [SizePackController::class, 'delete']);
        });

        Route::prefix('banner')->group(function() {
            Route::post('/create', [BannerController::class, 'create']);
            Route::put('/update/{banner:id}', [BannerController::class, 'update']);
            Route::delete('/delete/{banner:id}', [BannerController::class, 'delete']);
        });

        Route::prefix('setting')->group(function () {
            // main setting
            Route::post('/', [SettingController::class, 'setting']);

            //second banner
            Route::post('/second_banner/create', [SecondBannerController::class, 'create']);
            Route::put('/second_banner/update/{second_banner:id}', [SecondBannerController::class, 'update']);

            // footer banner 
            Route::post('/footer_banner', [FooterBannerController::class, 'footer_banner']);
        });

        Route::prefix('user')->group(function() {
            Route::get('/fetch', [GetUserController::class, 'fetch']);
            Route::get('/customer', [GetUserController::class, 'get_customer']);
            Route::get('/staff', [GetUserController::class, 'get_staff']);
            Route::get('/show/{user:id}', [GetUserController::class, 'show']);
            Route::put('/update/{user:id}', UpdateUserController::class);
            Route::delete('/delete/{user:id}', DeleteUserController::class);
            Route::post('/create_staff', [CreateUserController::class, 'staff']);

            Route::prefix('address')->group(function() {
                Route::post('/create', [UserAddressController::class, 'create']);
                Route::get('/fetch', [UserAddressController::class, 'fetch']);
                Route::get('/show/{user_address:id}', [UserAddressController::class, 'show']);
                Route::put('/update/{user_address:id}', [UserAddressController::class, 'update']);
                Route::delete('/delete/{user_address:id}', [UserAddressController::class, 'delete']);
            });
        });

        Route::prefix('product')->group(function() {
            Route::post('create', CreateProductController::class);
            Route::put('update/{product:id}', [UpdateProudctController::class, 'update']);
            Route::delete('delete/{product:id}', [DeleteProductController::class, 'delete']);
        });

        Route::prefix('carts')->group(function () {
            Route::post('create', CreateCartController::class);
            Route::delete('delete/{cart:id}', DeleteCartController::class);
            Route::get('/', [GetCartController::class, 'get']);
            Route::patch('/update_quantity/{cart:id}', [UpdateCartController::class, 'update_quantity']);
        });

        Route::prefix('discount')->group(function() {
            Route::post('/create', [DiscountController::class, 'create']);
            Route::patch('/update/{discount:id}', [DiscountController::class, 'update']);
        });

        Route::prefix('shipping_discount')->group(function() {
            Route::post('/set', [ShippingDiscountController::class, 'shipping_discount']);
        });

        Route::prefix('role')->group(function() {
            Route::get('/fetch', [RoleController::class, 'get']);
        });

        Route::prefix('transaction')->group(function() {
            Route::post('/checkout', [TransactionController::class, 'checkout']);
        });
    });
// end with auth
    Route::post('transaction/handle_moota', [TransactionController::class, 'handle_moota']);
