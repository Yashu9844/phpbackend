<?php
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['message'=>'testing the working']);
});

Route::view('/{path}','welcome');

