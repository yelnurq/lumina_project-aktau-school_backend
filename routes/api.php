<?php

use App\Http\Controllers\AuthController;
use App\Http\Middleware\TokenCheck;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TicketController;

Route::post("/login", [AuthController::class, "login"]);
Route::post("/register", [AuthController::class, "register"]);
Route::post('/logout', [AuthController::class, "logout"]);


Route::get('/news/home', [NewsController::class, 'home']);

Route::get('/news', [NewsController::class, 'index']);
Route::get('/news/search', [NewsController::class, 'search']);
Route::get('/news/{slug}', [NewsController::class, 'show']);
Route::put('/news/{slug}', [NewsController::class, 'update']);
Route::patch('/news/{slug}', [NewsController::class, 'update']);


Route::get('/categories', [CategoryController::class, 'index']);
Route::post('/categories', [CategoryController::class, 'store']);
Route::get('/categories/{slug}', [CategoryController::class, 'show']);
Route::put('/categories/{slug}', [CategoryController::class, 'update']);
Route::delete('/categories/{slug}', [CategoryController::class, 'destroy']);


Route::post('/ticket', [TicketController::class, 'store']);


Route::middleware(TokenCheck::class)->group(function () {
    Route::get('/admin/messages', [TicketController::class, 'getAllData']);
    
    
    Route::delete('/admin/news/{slug}', [NewsController::class, 'destroy']);
    Route::post('/news', [NewsController::class, 'store']);
    Route::get('/admin/categories', [CategoryController::class, 'admin']);
    Route::get('/admin/news/views', [NewsController::class, 'indexWithViews']);

});
Route::post("/logout", [AuthController::class, "logout"])->middleware(TokenCheck::class);