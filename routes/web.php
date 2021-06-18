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

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Route::resource('rest', 'App\Http\Controllers\RestTestController')->names('restTest');

Route::group(['namespace' => 'App\Http\Controllers\Blog', 'prefix' => 'blog'], function() {
    Route::resource('posts', 'PostController')->names('blog.posts');
});

//Админка Блога /admin/blog/categories
$groupData = [
    'namespace' => 'App\Http\Controllers\Blog\Admin',
    'prefix'    => 'admin/blog',
];
Route::group($groupData, function () {
    //BlogCategory
    $methods = ['index', 'edit', 'store', 'update', 'create'];
    Route::resource('categories', 'CategoryController')
        ->only($methods)
        ->names('blog.admin.categories');
});
