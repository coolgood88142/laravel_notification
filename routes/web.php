<?php

use Illuminate\Support\Facades\Route;
use App\Events\StatuLiked;


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

Route::get('/logout', 'HomeController@logout');

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/city', 'CityController@getCityData');

Route::get('/add', 'ArticlesController@showAdd');

Route::get('/articles', 'ArticlesController@showAritcles')->name('showAritcles');

Route::post('/addArticles', 'ArticlesController@addArticles');

Route::post('/readArticles', 'ArticlesController@readArticles')->name('readArticles');

Route::post('/deleteArticles', 'ArticlesController@deleteArticles')->name('deleteArticles');

Route::post('/saveArticles', 'ArticlesController@saveArticles');

Route::post('/addComment', 'CommentController@addComment');

Route::post('/showNotification', 'ArticlesController@showNotification')->name('showNotification');

Route::get('/testAdd', 'ArticlesController@testAdd');

Route::get('/readArticles', 'ArticlesController@readArticles');

Route::get('/deleteArticles', 'ArticlesController@deleteArticles');

Route::get('/showArticleContent', 'ArticlesController@showArticleContent')->name('showArticleContent');

Route::get('/editComment', 'CommentController@editComment');

Route::get('/showNotification', 'ArticlesController@showNotification');

Route::get('/sendNotification', 'ArticlesController@sendNotification');

Route::get('/addChannels', 'ChannelsController@showAddChannels');

Route::post('/addChannels', 'ChannelsController@addChannels');

Route::get('/showChannelContent', 'ChannelsController@showChannelContent')->name('showChannelContent');

Route::post('/getNotificationData', 'ArticlesController@getNotificationData')->name('getNotificationData');

Route::post('/getNotificationDataCount', 'ArticlesController@getNotificationDataCount')->name('getNotificationDataCount');

Route::get('/getNotificationDataCount', 'ArticlesController@getNotificationDataCount');

Route::get('test', function () {
    event(new App\Events\MyEvent('hello world'));
    return "Event has been sent!";
});

Route::get('/notification', function () {
    return view('notification');
});

Route::get('/send', 'PusherNotificationController@notification');

Route::get('/t', function () {
    event(new \App\Events\RedisMessage());
    dd('Event Run Successfully.');
});

Route::get('/changeOption', 'ArticlesController@changeOption');

Route::get('/redis', function () {
    return view('redis');
});

Route::get('/articlesRedis', 'ArticlesController@showAritclesRedis');
