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



Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
Route::get('/home', 'HomeController@index')->name('home_page');

Route::middleware('auth')->group(function () {
    Route::get('/sent_tasks','SentTaskController@SentTasks')->name('sent_tasks');
    Route::get('/received_tasks','ReceivedTaskController@ReceivedTasks')->name('received_tasks');
    Route::post('/sort_list','TlistController@sort_list')->name('sort_list');
    Route::post('/sort_task','TaskController@sort_task')->name('sort_task');
    Route::resource('/list', 'TlistController');
    Route::resource('/task', 'TaskController');
    Route::get('/update_status_view','UpdateStatusController@index')->name('update_status_view');
    Route::post('/update_status/{id}','UpdateStatusController@UpdateStatus')->name('update_status');
    Route::get('/comments_view/{id}','CommentController@index')->name('comments_view');
    Route::post('/comment/{id}','CommentController@CreateComment')->name('comment');
    Route::post('/delete_comment/{id}','CommentController@DeleteComment')->name('delete_comment');
});