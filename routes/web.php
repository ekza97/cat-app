<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BackApp\ExamController;
use App\Http\Controllers\BackApp\RoleController;
use App\Http\Controllers\BackApp\SoalController;
use App\Http\Controllers\BackApp\UserController;
use App\Http\Controllers\BackApp\KelasController;
use App\Http\Controllers\BackApp\MapelController;
use App\Http\Controllers\BackApp\StudentController;
use App\Http\Controllers\BackApp\TeacherController;
use App\Http\Controllers\BackApp\PermissionController;
use App\Http\Controllers\BackApp\StudentHasExamController;

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
    return to_route('login');
});

Auth::routes([
    'verify'=>false
]);

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    //Master Data Menu
    Route::resource('courses', MapelController::class)->except(['create','show']);
    Route::resource('classroom', KelasController::class)->except(['create','show']);
    Route::resource('students', StudentController::class)->except(['create','show']);
    Route::resource('teachers', TeacherController::class)->except(['create']);
    Route::post('teachers/set-mapel',[TeacherController::class,'setMapel'])->name('teachers.set_mapel');
    Route::resource('questions', SoalController::class)->except(['create','show']);
    Route::resource('exams', ExamController::class)->except(['create','show']);
    Route::resource('exam-students', StudentHasExamController::class)->except(['show']);

    //setting menu
    Route::resource('users', UserController::class)->except(['create','show']);
    Route::resource('roles', RoleController::class)->except(['create','show']);
    Route::resource('permissions', PermissionController::class)->only(['index','store','destroy']);
});