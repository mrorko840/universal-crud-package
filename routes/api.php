<?php

use Illuminate\Support\Facades\Route;
use UniversalCrud\Http\Controllers\UniversalCrudController;

Route::prefix('api/' . config('universal-crud.base_uri'))
    ->middleware(config('universal-crud.auth_middleware'))
    ->group(function () {

        Route::get('tables', [UniversalCrudController::class, 'tables']);
        Route::get('tables/{table}/columns', [UniversalCrudController::class, 'columns']);

        Route::get('{table}', [UniversalCrudController::class, 'index']);
        Route::get('{table}/{id}', [UniversalCrudController::class, 'show']);
        Route::post('{table}', [UniversalCrudController::class, 'store']);
        Route::post('{table}/{id}', [UniversalCrudController::class, 'update']);
        Route::delete('{table}/{id}', [UniversalCrudController::class, 'destroy']);
    });
