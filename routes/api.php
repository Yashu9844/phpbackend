<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');




Route::post('/auth/signup', [AuthController::class, 'signup']);
Route::post('/auth/signin',[AuthController::class,'signin'])->name('login');


Route::middleware(['auth:api'])->group(function () {
    Route::put('/user/{userId}', [UserController::class, 'updateUser']);

});

Route::middleware('auth:sanctum')->post('/user/logout', [UserController::class, 'signout']);


Route::delete('/user/{id}',[UserController::class,'deleteUser']);

Route::get('/users', [UserController::class, 'getUsers']);

Route::get('/user/{id}',[UserController::class,'getUser']);

