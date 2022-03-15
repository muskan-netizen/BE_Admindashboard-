<?php

Route::group(['prefix' => 'v1/v2', 'middleware' => ['ApiLocalization']], function () {
    Route::group(['middleware' => ['dbCheck', 'checkAuth', 'apilogger']], function() {

        Route::get('category/{id?}', 'Api\v1\v2\CategoryController@categoryData');
        Route::post('category/filters/{id?}', 'Api\v1\v2\CategoryController@categoryFilters');

    });
    Route::group(['middleware' => ['dbCheck','systemAuth', 'apilogger']], function() {
        
    });
});
