<?php

use App\Http\Controllers\Admin\MainController;
use App\Http\Controllers\Admin\SchoolController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\scheduler\RefController;
use App\Http\Middleware\CheckUserRole;
use Illuminate\Support\Facades\Route;




Route::get('/login',[UserController::class, 'login'])->name('login');
Route::get('/logout',[UserController::class, 'logout'])->name('logout');

Route::get('/',[UserController::class, 'start'])->name('home')->middleware(['auth']);
Route::post('/login',[UserController::class, 'authenticate'])->name('login.authenticate');
Route::get('/profile',[UserController::class, 'profile'])->name('profile')->middleware(['auth']);
Route::post('/profile_chpwd',[UserController::class, 'chpwd'])->name('chpwd')->middleware(['auth']);
Route::post('/profile_chemail',[UserController::class, 'chemail'])->name('chemail')->middleware(['auth']);

Route::get('/refs', [RefController::class, 'index'])->name('refs.index')->middleware(['auth', 'role:scheduler']);
Route::get('/refs/classes', [RefController::class, 'classes'])->name('refs.classes')->middleware(['auth', 'role:scheduler']);
Route::post('/refs/classes', [RefController::class, 'store_classes'])->name('refs.classes.store')->middleware(['auth', 'role:scheduler']);
Route::post('/refs/groups', [RefController::class, 'store_groups'])->name('refs.groups.store')->middleware(['auth', 'role:scheduler']);
Route::get('/refs/employers', [RefController::class, 'employers'])->name('refs.employers')->middleware(['auth', 'role:scheduler']);
Route::post('/refs/employers', [RefController::class, 'store_employers'])->name('refs.employers.store')->middleware(['auth', 'role:scheduler']);
Route::get('/refs/rooms', [RefController::class, 'rooms'])->name('refs.rooms')->middleware(['auth', 'role:scheduler']);
Route::post('/refs/rooms', [RefController::class, 'store_rooms'])->name('refs.rooms.store')->middleware(['auth', 'role:scheduler']);
Route::get('/refs/rings', [RefController::class, 'rings'])->name('refs.rings')->middleware(['auth', 'role:scheduler']);
Route::post('/refs/rings', [RefController::class, 'store_rings'])->name('refs.rings.store')->middleware(['auth', 'role:scheduler']);
Route::get('/refs/lessons', [RefController::class, 'lessons'])->name('refs.lessons')->middleware(['auth', 'role:scheduler']);
Route::post('/refs/lessons', [RefController::class, 'store_lessons'])->name('refs.lessons.store')->middleware(['auth', 'role:scheduler']);
Route::get('/refs/plans', [RefController::class, 'plans'])->name('refs.plans')->middleware(['auth', 'role:scheduler']);
Route::post('/refs/plans', [RefController::class, 'store_plans'])->name('refs.plans.store')->middleware(['auth', 'role:scheduler']);
//Route::get('/refs/periods', [RefController::class, 'periods'])->name('refs.periods')->middleware(['auth', 'role:scheduler']);
//Route::post('/refs/periods', [RefController::class, 'store_periods'])->name('refs.periods.store')->middleware(['auth', 'role:scheduler']);








/***************************
 * Work with user
 * All users have role:
 *  admin can input Scholls, scheduler users for schools
 *  scheduler can input teacher user for its school,
 *  teacher cannot input any user
 *
 */
Route::get('/admin', [MainController::class, 'index'])->name('admin.main.index')->middleware(['auth', 'role:admin']);
Route::resource('/admin/schools', SchoolController::class)->middleware(['auth', 'role:admin']);
Route::get('/admin/users', [UserController::class, 'admin_users'])->name('admin.users.index')->middleware(['auth', 'role:admin']);
Route::get('/admin/users/create', [UserController::class, 'admin_users_create'])->name('admin.users.create')->middleware(['auth', 'role:admin']);
Route::post('/admin/users', [UserController::class, 'admin_users_store'])->name('admin.users.store')->middleware(['auth', 'role:admin']);

// Route::get('/scheduler/users', [UserController::class, 'scheduler_users'])->name('scheduler.users.index')->middleware(['auth']);
// Route::get('/scheduler/users/create', [UserController::class, 'scheduler_users_create'])->name('scheduler.users.create')->middleware(['auth']);
