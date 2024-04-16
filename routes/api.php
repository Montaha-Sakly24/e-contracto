<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ContractCategoryController;
use App\Http\Controllers\ResetForgotPasswordController;

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
    Route::post('register','signUp');
});
Route::post('/forgotpassword', [ResetForgotPasswordController::class, 'forgotPassword']);
Route::post('/resetpassword', [ResetForgotPasswordController::class, 'resetPassword']);

Route::apiResource('permissions', 'PermissionController');

Route::prefix('users')->group(function () {
    Route::post('/', [UserController::class, 'createAdminAccount']);
    Route::get('/admin', [UserController::class, 'getAdminsList']);
    Route::get('/', [UserController::class, 'getUsersList']);
    Route::get('/search', [UserController::class, 'searchUser']);
    Route::get('/{user}', [UserController::class, 'getUser']);
    Route::delete('/{user}', [UserController::class, 'deleteUser']);
    Route::put('/{user}', [UserController::class, 'activateDeactivateUserAccount']);

});
Route::prefix('profile')->group(function () {
    Route::get('/{user}', [ProfileController::class, 'getProfile']);
    Route::put('/{user}', [ProfileController::class, 'updateProfile']);
    Route::delete('/{user}',[ProfileController::class, 'deleteProfile']);
});



//Route::apiResource('categories', 'ContractCategoryController');
Route::prefix('categories')->group(function () {
    Route::post('/', [ContractCategoryController::class, 'createCategory']);
    Route::get('/', [ContractCategoryController::class, 'showlisteCategories']);
    Route::get('/{category}', [ContractCategoryController::class, 'showCategory']);
    Route::put('/{category}', [ContractCategoryController::class, 'updateCategory']);
    Route::get('/search', [ContractCategoryController::class, 'searchCategory']);
    Route::delete('/{category}', [ContractCategoryController::class, 'deleteCategory']);
});
