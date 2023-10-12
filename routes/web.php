<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\UserController;
use App\Models\Blog;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth',)->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::middleware(['auth', 'role:admin'])->group(function(){
    Route::get('/admin/add_post', [BlogController::class, 'AddPost'])->name('admin.add_post');
    Route::post('/admin/add_post', [BlogController::class, 'store'])->name('blog.store');
    Route::get('/admin/add_category', [BlogController::class, 'addCategory'])->name('admin.add_category');
    Route::post('/admin/add_category', [BlogController::class, 'storeCategory'])->name('category.store');
    Route::get('/admin/dashboard', [AdminController::class, 'AdminDashboard'])->name('admin.dashboard');
});

Route::middleware(['auth', 'role:user'])->group(function(){
    Route::get('/user/dashboard', [UserController::class, 'UserDashboard'])->name('user.dashboard');
});

Route::get('/blogs', function (){
    $blogs = Blog::all();
    return view('blogs', ['blogs' => $blogs]);
})->name('blog');



Route::get('/blog/{id}', [BlogController::class, 'showBlog'])->where('id', '([0-9]+|[a-zA-Z\-]+)')
                                                            ->name('blogs');

Route::get('/category/{title}', [BlogController::class, 'showCategory']);