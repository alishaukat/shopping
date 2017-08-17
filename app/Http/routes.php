<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/',function(){
    return redirect()->route('products');
})->name('home');

Route::group(['prefix' => 'products'], function () {
    Route::get('/', 'ProductController@index')->name('products');
    Route::get('listing', 'ProductController@listing')->name('products.listing');
    Route::get('show/{slug}', 'ProductController@show')->name('products.show');
});

Route::group(['prefix' => 'cart'], function () {
    Route::get('/', 'CartController@index')->name('cart');
    Route::get('add/{id?}', 'CartController@add')->name('cart.add');
    Route::post('update-qty/{id?}', 'CartController@updateQty')->name('cart.update-qty');
    Route::get('remove/{id?}', 'CartController@remove')->name('cart.remove');
    Route::get('empty', 'CartController@emptyCart')->name('cart.empty');
});

Route::group(['prefix' => 'favorites'], function () {
    Route::get('/', 'FavoritesController@index')->name('favorites');
    Route::get('add/{id?}', 'FavoritesController@add')->name('favorites.add');
    Route::get('remove/{id?}', 'FavoritesController@remove')->name('favorites.remove');
    Route::get('empty', 'FavoritesController@emptyCart')->name('favorites.empty');
});