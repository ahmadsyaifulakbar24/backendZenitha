<?php

use App\Http\Controllers\API\Article\CreateArticleController;
use App\Http\Controllers\API\Article\DeleteArticleController;
use App\Http\Controllers\API\Article\GetArticleController;
use App\Http\Controllers\API\Article\UpdateArticleController;
use App\Http\Controllers\API\ArticleFile\ArticleFileController;
use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\Auth\LogoutController;
use App\Http\Controllers\API\Auth\RegisterController;
use App\Http\Controllers\API\Auth\UserController;
use App\Http\Controllers\API\Cart\CreateCartController;
use App\Http\Controllers\API\Cart\DeleteCartController;
use App\Http\Controllers\API\Cart\GetCartController;
use App\Http\Controllers\API\Cart\UpdateCartController;
use App\Http\Controllers\API\Courier\CourierController;
use App\Http\Controllers\API\Discount\DiscountController;
use App\Http\Controllers\API\Discount\ShippingDiscountController;
use App\Http\Controllers\API\MasterData\VariantController;
use App\Http\Controllers\API\MasterData\CategoryController;
use App\Http\Controllers\API\MasterData\SizePackController;
use App\Http\Controllers\API\MasterData\SubCategoryController;
use App\Http\Controllers\API\MasterData\VariantOptionController;
use App\Http\Controllers\API\Moota\MootaController;
use App\Http\Controllers\API\Product\CreateProductController;
use App\Http\Controllers\API\Product\DeleteProductController;
use App\Http\Controllers\API\Product\GetProductController;
use App\Http\Controllers\API\Product\ProductVariantOptionController;
use App\Http\Controllers\API\Product\UpdateProductController;
use App\Http\Controllers\API\ProductSlider\ProductSliderController;
use App\Http\Controllers\API\Region\RegionController;
use App\Http\Controllers\API\Report\ReportController;
use App\Http\Controllers\API\Role\RoleController;
use App\Http\Controllers\API\Transaction\PaymentController;
use App\Http\Controllers\API\Transaction\TransactionController;
use App\Http\Controllers\API\User\CreateUserController;
use App\Http\Controllers\API\User\DeleteUserController;
use App\Http\Controllers\API\User\GetUserController;
use App\Http\Controllers\API\User\ParentUserController;
use App\Http\Controllers\API\User\ResetPasswordController;
use App\Http\Controllers\API\User\UpdateUserController;
use App\Http\Controllers\API\User\UserAddressController;
use App\Http\Controllers\API\UserWishlist\UserWishlistController;
use App\Http\Controllers\API\WebSetting\BannerController;
use App\Http\Controllers\API\WebSetting\OtherSetting\FooterBannerController;
use App\Http\Controllers\API\WebSetting\OtherSetting\SecondBannerController;
use App\Http\Controllers\API\WebSetting\SettingController;
use App\Http\Controllers\ShippingController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

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

if(App::environment('production')) {
    URL::forceScheme('https');
}
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
        Route::post('/reset_password/mail', [ResetPasswordController::class, 'reset_password_mail']);
        Route::post('/reset_password/token', [ResetPasswordController::class, 'reset_password_token']);
    });

    Route::prefix('article')->group(function() {
        Route::get('fetch', [GetArticleController::class, 'get']);
        Route::get('show/{article:slug}', [GetArticleController::class, 'show']);
    });

    Route::prefix('product_slider')->group(function() {
        Route::get('fetch', [ProductSliderController::class, 'get']);
    });
// end without auth


// with auth
    Route::middleware(['auth:api'])->group(function () {
        Route::prefix('auth')->group( function() {
            Route::get('/user', UserController::class);
            Route::delete('/logout', LogoutController::class);
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
            Route::delete('/delete/{variant_option:id}', [VariantOptionController::class, 'delete']);
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
            Route::post('reset_password/without_confirmation', [ResetPasswordController::class, 'without_confirmation']);
            Route::post('reset_password/with_old_password', [ResetPasswordController::class, 'with_old_password']);

            Route::prefix('parent')->group(function() {
                Route::post('/set/{user_id?}', [ParentUserController::class, 'parent']);
                Route::delete('/delete', [ParentUserController::class, 'delete']);
            });

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
            Route::put('update/{product:id}', [UpdateProductController::class, 'update']);
            Route::patch('discount/update/{product:id}', [UpdateProductController::class, 'update_discount']);
            Route::delete('delete/{product:id}', [DeleteProductController::class, 'delete']);
            Route::patch('stock/add/{product_combination:product_slug}', [UpdateProductController::class, 'add_stock']);
            Route::patch('stock/update/{product_combination:product_slug}', [UpdateProductController::class, 'update_stock']);
        });

        Route::prefix('carts')->group(function () {
            Route::post('create', CreateCartController::class);
            Route::delete('delete/{cart:id}', DeleteCartController::class);
            Route::get('/', [GetCartController::class, 'get']);
            Route::patch('/update_quantity/{cart:id}', [UpdateCartController::class, 'update_quantity']);
        });

        Route::prefix('discount')->group(function() {
            Route::get('/other_discount', [DiscountController::class, 'get_other_discount']);
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
            Route::get('/fetch', [TransactionController::class, 'get']);
            Route::get('/search', [TransactionController::class, 'search']);
            Route::post('/checkout', [TransactionController::class, 'checkout']);
            Route::get('/show/{transaction:id}', [TransactionController::class, 'show']);
            Route::patch('/update_status/{transaction:id}', [TransactionController::class, 'update_status']);
            Route::patch('/update_resi/{transaction:id}', [TransactionController::class, 'update_resi']);
            Route::get('/notification', [TransactionController::class, 'notification']);

            Route::prefix('payment')->group(function() {
                Route::get('/', [PaymentController::class, 'get']);
                Route::get('show/{payment:id}', [PaymentController::class, 'show']);
                Route::patch('update_status/{payment:id}', [PaymentController::class, 'update_status']);
                Route::patch('second_payment_po/{payment:id}', [PaymentController::class, 'triger_payement_po']);
            });
        });

        Route::prefix('article')->group(function() {
            Route::post('create', CreateArticleController::class);
            Route::put('update/{article:slug}', UpdateArticleController::class);
            Route::delete('delete/{article:slug}', DeleteArticleController::class);
        });

        Route::prefix('article_file')->group(function() {
            Route::get('fetch', [ArticleFileController::class, 'get']);
            Route::get('show/{article_file:id}', [ArticleFileController::class, 'show']);
            Route::post('create', [ArticleFileController::class, 'create']);
            Route::delete('delete/{article_file:id}', [ArticleFileController::class, 'delete']);
        });

        Route::prefix('user_wishlist')->group(function() {
            Route::get('fetch', [UserWishlistController::class, 'get']);
            Route::get('show', [UserWishlistController::class, 'show']);
            Route::post('wishlist', [UserWishlistController::class, 'wishlist']);
            Route::delete('delete/{user_wishlist:id}', [UserWishlistController::class, 'delete']);
        });

        Route::prefix('product_slider')->group(function() {
            Route::post('create', [ProductSliderController::class, 'create']);
            Route::delete('delete/{product_slider:id}', [ProductSliderController::class, 'delete']);
        });

        Route::prefix('courier')->group(function() {
            Route::get('fetch', [CourierController::class, 'get']);
            Route::patch('update_active', [CourierController::class, 'update_active']);
        });

        Route::prefix('shipping')->group(function() {
            Route::post('cost', [ShippingController::class, 'get_cost']);
            Route::post('waybill', [ShippingController::class, 'get_waybill']);
        });

        Route::prefix('moota')->group(function() {
            Route::get('bank', [MootaController::class, 'bank']);
        });

        Route::prefix('report')->group(function(){
            Route::get('activity_transaction', [ReportController::class, 'activity_transaction']);
            Route::get('turnover', [ReportController::class, 'turnover']);
            Route::get('sales', [ReportController::class, 'sales']);
        });
    });
// end with auth
    Route::post('transaction/handle_moota', [TransactionController::class, 'handle_moota']);
