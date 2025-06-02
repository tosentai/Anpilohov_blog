<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RestTestController;
use App\Http\Controllers\Blog\PostController;
use App\Http\Controllers\Blog\Admin\CategoryController as AdminCategoryController; // <-- Важливо використовувати псевдонім
use App\Http\Controllers\Blog\Admin\PostController as AdminPostController;
use App\Http\Controllers\Blog\Admin\MainController as AdminMainController; // <-- Якщо у вас є MainController для адмінки


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
    'namespace' => 'App\Http\Controllers\Blog',
    'prefix' => 'blog'
], function () {
    Route::resource('posts', PostController::class)->names('blog.posts');
});

// Адмін-панель блогу
$groupData = [
    // 'namespace' => 'App\Http\Controllers\Blog\Admin', // Цей рядок має бути закоментований або видалений
    'prefix' => 'admin/blog',
    'as' => 'blog.admin.',
];
Route::group($groupData, function () {
    // Головна сторінка адмінки (якщо є)
    // Route::get('/', [AdminMainController::class, 'index'])->name('index'); // Розкоментуйте, якщо використовуєте

    // BlogCategory
    // *** ПЕРЕКОНАЙТЕСЯ, ЩО ЦЕЙ МАСИВ МІСТИТЬ 'destroy' ***
    $methods = ['index', 'edit', 'store', 'update', 'create', 'destroy'];
    Route::resource('categories', AdminCategoryController::class) // <-- Використовуйте AdminCategoryController
    ->only($methods)
        ->names('categories');

    // BlogPost (Статті блогу для адмінки)
    Route::resource('posts', AdminPostController::class)
        ->except(['show'])
        ->names('posts');
});
