<?php

use Illuminate\Support\Facades\Route;
use Hashtag\UniversalCrud\Http\Controllers\UniversalCrudController;

Route::group([
    'prefix' => config('universal-crud.base_uri'),
    'middleware' => config('universal-crud.auth_middleware'),
], function () {
    Route::get('tables', [UniversalCrudController::class, 'tables']);
    Route::get('tables/{table}/columns', [UniversalCrudController::class, 'columns']);

    Route::get('{table}', [UniversalCrudController::class, 'index']);
    Route::get('{table}/{id}', [UniversalCrudController::class, 'show']);
    Route::post('{table}', [UniversalCrudController::class, 'store']);
    Route::post('{table}/{id}', [UniversalCrudController::class, 'update']);
    Route::delete('{table}/{id}', [UniversalCrudController::class, 'destroy']);
});
