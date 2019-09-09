<?php

use KSUGMap\Repositories\Stories;

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

Route::get('/stories', function (Stories $stories) {
    return $stories->all();
});

Route::get('/stories/{story_id}/comments', 'StoryCommentsController@index');
Route::post('/stories/{story_id}/comments', 'StoryCommentsController@store');

Route::get('/{vue?}', function () {
    return view('master');
})->where('vue', '[\/\w\.-]*')->where('vue', '^((?!admin).)*$');
