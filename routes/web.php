<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ShowPages;
use App\Http\Controllers\WeightPages;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('Auth.login');
});

Route::get('/show/{id}', [ShowPages::class, 'showrecorduser'])->name('show');

Route::group(['prefix' => 'auth'], function () {
    Route::get('/login', [AuthController::class, 'showlogin'])->name('login.form');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/backup', [AuthController::class, 'create_backup'])->name('backup');
});

Route::get('/dashboard', [ShowPages::class, 'showdashboard'])->name('dashboard');
Route::get('/first', [ShowPages::class, 'showfirst'])->name('first.weight');
Route::post('/first', [WeightPages::class, 'savefirst'])->name('save.first.weight');
Route::get('/second', [ShowPages::class, 'showsecond'])->name('second.weight');
Route::post('/second', [WeightPages::class, 'savesecond'])->name('save.second.weight');
Route::get('/print', [ShowPages::class, 'showprint'])->name('print.record');
Route::post('/print', [WeightPages::class, 'print'])->name('print.post');
Route::get('/printing/{id}', [ShowPages::class, 'showprintlayout'])->name('print.layout');
Route::get('/edit', [ShowPages::class, 'showEdit'])->name('edit.page');
Route::post('/update', [WeightPages::class, 'updateRecord'])->name('update.record');

Route::get('/records', [ShowPages::class, 'recordsPage'])->name('records.page');
Route::get('/records/periods', [ShowPages::class, 'periodOptions'])->name('records.periods');
Route::get('/records/fetch',   [ShowPages::class, 'fetchRecords'])->name('records.fetch');

Route::post('/get/record', [WeightPages::class, 'fetchRecord'])->name('get.record');

Route::get('/delete/record', [ShowPages::class, 'showdelete'])->name('delete.page');
Route::post('/delete/record', [WeightPages::class, 'deleteRecord'])->name('delete.post');

Route::get('/add/user', [ShowPages::class, 'addnewuser'])->name('add.new.user');
Route::post('/add/user', [WeightPages::class, 'savenewuser'])->name('save.new.user');