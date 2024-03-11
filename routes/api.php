<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ContractCategoryController;


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

Route::controller(AuthController::class)->group(function(){
    Route::post('login','login');
    Route::post('register','register');
});

Route::apiResource('permissions', 'PermissionController');

//

// l code hadha l kol najem n3wdhu b une seule ligne tasna3homlk lkol automatiquement:
//Route::apiResource('categories', 'ContractCategoryController');
Route::prefix('categories')->group(function () {
    Route::post('/', [ContractCategoryController::class, 'createCategory']);
    Route::get('/', [ContractCategoryController::class, 'showlisteCategories']);
    Route::get('/{category}', [ContractCategoryController::class, 'showCategory']);
    Route::put('/{category}', [ContractCategoryController::class, 'updateCategory']);
    Route::delete('/{category}', [ContractCategoryController::class, 'deleteCategory']);
});
