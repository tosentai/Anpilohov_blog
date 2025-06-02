<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RestTestController;
use App\Http\Controllers\Blog\PostController;
use App\Http\Controllers\Blog\Admin\CategoryController;
use App\Http\Controllers\Blog\Admin\PostController as AdminPostController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::resource('rest', RestTestController::class)->names('restTest');

Route::group([
    'namespace' => 'App\Http\Controllers\Blog', // Це вказує на простір імен
    'prefix' => 'blog'
], function () {
    Route::resource('posts', PostController::class)->names('blog.posts');
});

// Адмін-панель блогу
$groupData = [
    // 'namespace' => 'App\Http\Controllers\Blog\Admin', // Цей рядок має бути закоментований або видалений
    'prefix' => 'admin/blog',
    'as' => 'blog.admin.', // <--- ЦЕЙ ПРЕФІКС ДЛЯ ІМЕН МАРШРУТІВ
];
Route::group($groupData, function () {
    // BlogCategory
    $methods = ['index','edit','store','update','create',];
    Route::resource('categories', CategoryController::class)
        ->only($methods)
        ->names('categories'); // <--- ЗМІНІТЬ ТУТ: ПРОСТО 'categories'

    // BlogPost (Статті блогу для адмінки)
    Route::resource('posts', AdminPostController::class)
        ->except(['show'])
        ->names('posts'); // <--- ЗМІНІТЬ ТУТ: ПРОСТО 'posts'
});
