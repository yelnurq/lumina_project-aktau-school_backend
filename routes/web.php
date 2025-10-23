<?php

use App\Http\Controllers\RssController;
use Illuminate\Support\Facades\Route;

Route::get('/{any}', function () {
    return view('welcome'); // Убедись, что этот файл передает запрос в React
})->where('any', '.*');
Route::get('/rss', [RssController::class, 'feed']);
Route::get('/sitemap.xml', [RssController::class, 'sitemap']);
