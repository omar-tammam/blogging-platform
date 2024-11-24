<?php

use App\Http\Controllers\Admin\Article\ArticleAdminController;
use App\Http\Controllers\Admin\Category\CategoryController;
use App\Http\Controllers\User\Article\ArticleUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1'], function () {


    //--------------- Admin routes  ----------------//
    Route::group(['prefix' => 'admin'], function () {

        Route::group(['prefix' => 'categories'], function () {
            Route::get('/', [CategoryController::class, 'index']);
            Route::post('/', [CategoryController::class, 'store']);
            Route::get('/{id}', [CategoryController::class, 'show']);
            Route::put('/{id}', [CategoryController::class, 'update']);
            Route::delete('/{id}', [CategoryController::class, 'destroy']);
        });


        Route::group(['prefix' => 'articles'], function () {
            Route::get('/', [ArticleAdminController::class, 'index']);
            Route::post('/', [ArticleAdminController::class, 'store']);
            Route::get('/{id}', [ArticleAdminController::class, 'show']);
            Route::put('/{id}', [ArticleAdminController::class, 'update']);
            Route::delete('/{id}', [ArticleAdminController::class, 'destroy']);
        });

        Route::group(['prefix' => 'articles-viewers'], function () {
            Route::get('/', [ArticleAdminController::class, 'showViewers']);
        });
    });


    //-------------- User routes   ----------------//
    Route::group(['prefix' => 'user'], function () {
        Route::group(['prefix' => 'articles'], function () {
            Route::get('/', [ArticleUserController::class, 'index']);
        });

    });


});
