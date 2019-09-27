<?php

use KSUGMap\Repositories\Stories;
use KSUGMap\Repositories\Places;

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

Route::get('/search', 'SearchController');

Route::get('/stories', function (Stories $stories) {
    return $stories->all();
});

Route::get('/places', function (Places $places) {
    return $places->all();
});

Route::get('/places/{place_slug}/comments', 'PlaceCommentsController@index');
Route::post('/places/{place_slug}/comments', 'PlaceCommentsController@store');

Route::get('/{vue?}', function () {
    return view('master');
})->where([
    'vue' => '^((?!admin|nova-api).)*$',
]);
