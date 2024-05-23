<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResetForgotPasswordController;

use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\ContractCategoryController;
use App\Http\Controllers\PlaceholderController;
use App\Http\Controllers\GabaritController;
use App\Http\Controllers\ContractModelController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\SuperadminDashboardController;
use App\Http\Controllers\AdminDashboardController;



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

Route::prefix('users')->group(function () {
    Route::post('/', [UserController::class, 'createAdminAccount']);
    Route::get('/admin', [UserController::class, 'getAdminsList']);
    Route::get('/', [UserController::class, 'getUsersList']);
    Route::get('/{user}', [UserController::class, 'getUser']);
    Route::delete('/{user}', [UserController::class, 'deleteUser']);
    Route::put('/{user}', [UserController::class, 'changeAccountStatus']);
});

Route::prefix('profile')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [ProfileController::class, 'getProfile']);
    Route::put('/', [ProfileController::class, 'updateProfile']);
    Route::post('/', [ProfileController::class, 'changePassword']);
    Route::delete('/',[ProfileController::class, 'deleteProfile']);
});

Route::prefix('categories')->group(function () {
    Route::post('/', [ContractCategoryController::class, 'createCategory']);
    Route::get('/', [ContractCategoryController::class, 'getListCategories']);
    Route::get('/{category}', [ContractCategoryController::class, 'getCategory']);
    Route::put('/{category}', [ContractCategoryController::class, 'updateCategory']);
    Route::delete('/{category}', [ContractCategoryController::class, 'deleteCategory']);
});

Route::prefix('placeholders')->group(function () {
    Route::post('/', [PlaceholderController::class, 'createPlaceholder']);
    Route::get('/', [PlaceholderController::class, 'getPlaceholders']);
    Route::put('/{placeholder}', [PlaceholderController::class, 'updatePlaceholder']);
    Route::delete('/{placeholder}', [PlaceholderController::class, 'deletePlaceholder']);
});

Route::prefix('gabarits')->group(function () {
    Route::post('/', [GabaritController::class, 'createGabarit']);
    Route::get('/', [GabaritController::class, 'getAllGabarits']);
    Route::get('/{gabarit}', [GabaritController::class, 'getGabarit']);
    Route::put('/{gabarit}', [GabaritController::class, 'updateGabarit']);
    Route::delete('/{gabarit}', [GabaritController::class, 'deleteGabarit']);
});

Route::middleware('auth:sanctum')->post('/models', [ContractModelController::class, 'createModel']);


Route::prefix('models')->group(function () {
    Route::get('/', [ContractModelController::class, 'getAllModels']);
    Route::get('/{model}', [ContractModelController::class, 'getModel']);
    Route::get('/{modelId}/details', [ContractModelController::class, 'getModelDetails']);
    Route::put('/{model}', [ContractModelController::class, 'updateModel']);
    Route::delete('/{model}', [ContractModelController::class, 'deleteModel']);
    Route::post('/filter', [ContractModelController::class, 'filterModels']);
});

Route::prefix('contracts')->middleware('auth:sanctum')->group(function () {
    Route::post('/', [ContractController::class, 'generateContract']);
    Route::get('/user', [ContractController::class, 'getContracts']);}
);
Route::prefix('contracts')->group(function () {
    Route::get('/{contract}', [ContractController::class, 'getContract']);
    Route::put('/{contract}', [ContractController::class, 'updateContract']);
    Route::delete('/{contract}', [ContractController::class, 'deleteContract']);
    Route::get('/{contract}/export', [ContractController::class, 'exportContract']);
    Route::post('/{contract}/sign', [ContractController::class, 'signContract']);
    Route::post('/send-contract', [ContractController::class, 'sendContract']);

});


Route::prefix('superadmin')->group(function () {
    Route::get('admins/count', [SuperadminDashboardController::class, 'getAdminsCount']);
    Route::get('users/count', [SuperadminDashboardController::class, 'getSamplesUsersCount']);
    Route::get('models/count', [SuperadminDashboardController::class, 'getModelsCountByAdmin']);
    Route::get('contracts/count', [SuperadminDashboardController::class, 'getContractsCountBySampleUser']);
    Route::get('contracts/signed-percentage', [SuperadminDashboardController::class, 'getSignedContractsPercentage']);
    Route::get('contracts/count-last-four-weeks', [SuperadminDashboardController::class, 'getContractsCountLastFourWeeks']);
    Route::get('users/new-sample-users-by-month', [SuperadminDashboardController::class, 'getNewSampleUsersByMonth']);
});


Route::prefix('admin')->group(function () {
    Route::get('categories/count', [AdminDashboardController::class, 'getCategoriesCount']);
    Route::get('categories/models/count', [AdminDashboardController::class, 'getModelsCountByCategory']);
    Route::get('gabarits/usage-count', [AdminDashboardController::class, 'getGabaritUsageCount']);
    Route::get('contract-models/top', [AdminDashboardController::class, 'getTopContractModels']);
    Route::get('contracts/count', [AdminDashboardController::class, 'getContractsCountBySampleUser']);
});


