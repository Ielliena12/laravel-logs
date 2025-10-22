<?php

use Illuminate\Support\Facades\Route;
use Ielliena12\LaravelLogs\Http\Controllers\LogController;

Route::group([
    'prefix' => config('laravel-logs.route_path', 'admin/logs'),
    'middleware' => config('laravel-logs.middleware', ['web', 'auth'])
], function () {
    Route::get('/', [LogController::class, 'index'])->name('laravel-logs.index');
    Route::get('/{logType}', [LogController::class, 'show'])->name('laravel-logs.show');
    Route::get('/{logType}/{date}', [LogController::class, 'showByDate'])->name('laravel-logs.show.date');
    Route::post('/{logType}/{date}/delete', [LogController::class, 'delete'])->name('laravel-logs.delete');
});
