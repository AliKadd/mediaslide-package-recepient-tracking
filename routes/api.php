<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    AuthController, PackageController, RecipientController,
    CommentsController, EventsController, ModelsController
};

Route::post('login', [AuthController::class,'login']);

Route::prefix('packages')->group(function () {
    Route::get('view/{token}', [PackageController::class, 'showByToken']);
    Route::post('{token}/shortlist/{modelProfile}', [PackageController::class, 'shortlistModel']);
});
Route::get('models/{token}/download/{modelProfile}', [ModelsController::class, 'download']);

Route::middleware('auth:sanctum')->group(function(){
    Route::post('logout', [AuthController::class,'logout']);

    Route::apiResource('packages', PackageController::class);
    Route::get('packages/version/{packageVersion}', [PackageController::class, 'getVersion']);

    Route::post('packages/{package}/send', [RecipientController::class, 'sendPackage']);

    Route::apiResource('models', ModelsController::class);

    Route::post('comments', [CommentsController::class, 'store']);
    Route::get('comments/{packageVersion}', [CommentsController::class, 'list']);

    Route::post('events/store', [EventsController::class, 'store']);
});
