<?php
Route::group(['prefix' => 'v1', 'middleware' => ['ApiLocalization']], function () {
    Route::group(['middleware' => ['dbCheck', 'checkAuth', 'apilogger']], function() {

        Route::post('sendTestMail', 'Api\v1\BaseController@sendTestMail');

        Route::post('cart/add', 'Api\v1\CartController@add');
        Route::get('cart/list', 'Api\v1\CartController@index');
        Route::get('vendor/slots', 'Api\v1\CartController@checkScheduleSlots');
        Route::post('homepage', 'Api\v1\HomeController@homepage');
        Route::post('header', 'Api\v1\HomeController@headerContent');
        Route::get('product/{id}', 'Api\v1\ProductController@productById');
        Route::get('getAllProductTags', 'Api\v1\ProductController@getAllProductTags');
        Route::post('get-products', 'Api\v1\ProductController@productList');
        Route::get('cms/page/list', 'Api\v1\CMSPageController@getPageList');
        Route::get('brand/{id?}', 'Api\v1\BrandController@productsByBrand');
        Route::get('category/{id?}', 'Api\v1\CategoryController@categoryData');
        Route::post('search/{type}/{id?}', 'Api\v1\HomeController@globalSearch');
        Route::post('cms/page/detail', 'Api\v1\CMSPageController@getPageDetail');
        Route::post('brand/filters/{id?}', 'Api\v1\BrandController@brandFilters');
        Route::get('celebrity/{all?}', 'Api\v1\CelebrityController@celebrityList');
        Route::post('category/filters/{id?}', 'Api\v1\CategoryController@categoryFilters');
        Route::get('celebrityProducts/{id?}', 'Api\v1\CelebrityController@celebrityProducts');
        Route::post('celebrity/filters/{id?}', 'Api\v1\CelebrityController@celebrityFilters');
        Route::get('vendor/{id?}', 'Api\v1\VendorController@productsByVendor');
        Route::post('vendor/filters/{id?}', 'Api\v1\VendorController@vendorFilters');
        Route::post('vendor/category/list', 'Api\v1\VendorController@postVendorCategoryList');
        Route::post('vendor/vendorProductsFilter', 'Api\v1\VendorController@vendorProductsFilter');
        // Route::post('vendor/category/list', 'Api\v1\VendorController@postVendorCategoryList');
        Route::get('vendor/{slug1}/{slug2}', 'Api\v1\VendorController@vendorCategoryProducts');
        // Route::get('vendor/category/productsFilter/{slug1}/{slug2}', 'Api\v1\VendorController@vendorCategoryProductsFilter');
        Route::post('vendor/register', 'Api\v1\VendorController@postVendorRegister');
        Route::post('driver/register', 'Api\v1\AuthController@driverSignup');
        Route::post('checkIsolateSingleVendor', 'Api\v1\CartController@checkIsolateSingleVendor');
        Route::post('productByVariant/{id}','Api\v1\ProductController@getVariantData')->name('productVariant');
        Route::post('contact-us', 'Api\v1\HomeController@contactUs');

        Route::post('upload-image-pickup', 'Api\v1\PickupDeliveryController@uploadImagePickup');  ////// upload image while pickup delivery

        Route::post('cart/product/lastAdded', 'Api\v1\CartController@getLastAddedProductVariant');
	    Route::post('cart/product/variant/different-addons', 'Api\v1\CartController@getProductVariantWithDifferentAddons');

        Route::post('promo-code-open/list', 'Api\v1\PickupDeliveryController@postPromoCodeListOpen');

    });
    Route::group(['middleware' => ['dbCheck','systemAuth', 'apilogger']], function() {
        Route::get('cart/empty', 'Api\v1\CartController@emptyCart');
        Route::get('coupons/{id?}', 'Api\v1\CouponController@list');
        Route::post('cart/remove', 'Api\v1\CartController@removeItem');
        Route::get('cart/totalItems', 'Api\v1\CartController@getItemCount');
        Route::post('cart/updateQuantity', 'Api\v1\CartController@updateQuantity');
        Route::post('promo-code/list', 'Api\v1\PromoCodeController@postPromoCodeList');
        Route::post('promo-code/verify', 'Api\v1\PromoCodeController@postVerifyPromoCode');
        Route::post('promo-code/remove', 'Api\v1\PromoCodeController@postRemovePromoCode');
        Route::post('promo-code/validate_promo_code', 'Api\v1\PromoCodeController@validate_promo_code');
        Route::post('promo-code/vendor_promo_code', 'Api\v1\PromoCodeController@vendorPromoCodeList');
        Route::post('cart/product-schedule/update', 'Api\v1\CartController@updateProductSchedule');
    });
});
