<?php

use KSUGMap\Queries\ToursWithPlaces;
use KSUGMap\Repositories\Places;
use KSUGMap\Repositories\Stories;
use KSUGMap\Tour;

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

Route::resource('stories', 'StoryController')->only(['index', 'update']);

Route::get('/places', function (Places $places) {
    return $places->all();
});

Route::get('/tours', function (ToursWithPlaces $q) {
    return $q->get();
});

Route::get('/tour/{tour}/stories', 'TourStoriesController@index');

Route::get('/learning-resources', 'LearningResourceController@index');

Route::post('/comments', 'CommentController@store');

Route::get('/{vue?}', function () {
    return view('master');
})->where([
    'vue' => '^((?!admin|nova-api|media).)*$',
]);
