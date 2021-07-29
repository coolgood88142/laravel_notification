<?php

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/city', 'CityController@getCityData');

Route::get('/add', 'ArticlesController@showAdd');

Route::get('/articles', 'ArticlesController@showAritcles')->name('showAritcles');

Route::post('/addArticles', 'ArticlesController@addArticles');

Route::post('/readArticles', 'ArticlesController@readArticles');

Route::post('/deleteArticles', 'ArticlesController@deleteArticles');

Route::post('/saveArticles', 'ArticlesController@saveArticles');

Route::post('/addComment', 'CommentController@addComment');

Route::get('/testAdd', 'ArticlesController@testAdd');

Route::get('/readArticles', 'ArticlesController@readArticles');

Route::get('/deleteArticles', 'ArticlesController@deleteArticles');

Route::get('/showArticleContent', 'ArticlesController@showArticleContent')->name('showArticleContent');

Route::get('/editComment', 'CommentController@editComment');

Route::get('/showNotification', 'ArticlesController@showNotification');
