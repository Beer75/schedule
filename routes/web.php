<?php

use App\Http\Controllers\Admin\MainController;
use App\Http\Controllers\Admin\SchoolController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::get('/',[UserController::class, 'start'])->name('home')->middleware(['auth']);

Route::get('/login',[UserController::class, 'login'])->name('login');
Route::get('/logout',[UserController::class, 'logout'])->name('logout');
Route::post('/login',[UserController::class, 'authenticate'])->name('login.authenticate');
Route::get('/profile',[UserController::class, 'profile'])->name('profile')->middleware(['auth']);

Route::post('/profile_chpwd',[UserController::class, 'chpwd'])->name('chpwd')->middleware(['auth']);
Route::post('/profile_chemail',[UserController::class, 'chemail'])->name('chemail')->middleware(['auth']);
//Route::get('/login',[UserController::class, 'authenticate'])->name('login.authenticate');


Route::get('/admin', [MainController::class, 'index'])->name('admin.main.index')->middleware(['auth']);

/***************************
 * Work with user
 * All users have role:
 *  admin can input Scholls, sheduler users for schools
 *  sheduler can input teacher user for its school,
 *  teacher cannot input any user
 *
 *
 *
 *
 *
 *
 */

Route::resource('/admin/schools', SchoolController::class);

Route::get('/admin/users', [UserController::class, 'admin_users'])->name('admin.users.index')->middleware(['auth']);
Route::get('/admin/users/create', [UserController::class, 'admin_users_create'])->name('admin.users.create')->middleware(['auth']);
Route::post('/admin/users', [UserController::class, 'admin_users_store'])->name('admin.users.store')->middleware(['auth']);

// Route::get('/sheduler/users', [UserController::class, 'sheduler_users'])->name('sheduler.users.index')->middleware(['auth']);
// Route::get('/sheduler/users/create', [UserController::class, 'sheduler_users_create'])->name('sheduler.users.create')->middleware(['auth']);
