<?php

use think\facade\Route;

Route::pattern([
    'id'   => '\d+',
]);

Route::group(':version/banner', function () {

    Route::get(':id', ':version.Banner/getBanner');

})->https();

Route::group(':version/theme', function () {

    Route::get('', ':version.Theme/getSimpleList');
    Route::get(':id', ':version.Theme/getComplexOne');

})->https();

Route::group(':version/product', function () {

    Route::get('', ':version.Product/getRecent');
    Route::get('/by_category/:id', ':version.Product/getAllByCategory');
    Route::get(':id', ':version.Product/getProductDetail');
    Route::get('/hot', ':version.Product/getHotToProduct');

})->https();


Route::group(':version/category', function () {

    Route::get('', ':version.Category/getAllCategories');
})->https();

Route::group(':version/token', function () {

    Route::post('/user', ':version.Token/getToken');

})->https();

Route::group(':version/address', function () {

    Route::post('add', ':version.Address/addAddress');
    Route::post('up', ':version.Address/updataAddress');
    Route::get('', ':version.Address/getAddress');

})->https();

Route::group(':version/order', function () {

    Route::post('', ':version.Order/placeOrder');
    Route::get('by_user', ':version.Order/getOrderByUser');
    Route::get('by_order', ':version.Order/getDetail');

})->https();

Route::group(':version/pay', function () {

    Route::post('', ':version.Pay/pay');
    Route::post('notify', ':version.Pay/receiveNotify');

})->https();


Route::get(':version/banner/get/info', ':version.Banner/getInfo');

//Route::get(':version/theme/:id',':version.Theme/getSimpleList');
//Route::group(function () {
//})->allowCrossDomain([/** 设置跨域允许的header头信息 */ 'Access-Control-Allow-Headers' => 'token']);
