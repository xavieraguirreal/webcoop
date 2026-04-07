<?php

use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SiteController::class, 'home'])->name('home');

Route::get('/nosotros/{slug}', [SiteController::class, 'page'])->name('page');

Route::get('/noticias', [SiteController::class, 'newsIndex'])->name('news.index');
Route::get('/noticias/categoria/{slug}', [SiteController::class, 'newsByCategory'])->name('news.category');
Route::get('/noticias/{slug}', [SiteController::class, 'newsShow'])->name('news.show');

Route::get('/relatos', [SiteController::class, 'storiesIndex'])->name('stories.index');
Route::get('/relatos/{slug}', [SiteController::class, 'storyShow'])->name('stories.show');

Route::get('/areas-de-trabajo', [SiteController::class, 'workAreas'])->name('work-areas');
Route::get('/areas/{slug}', [SiteController::class, 'workAreaShow'])->name('work-area.show');

Route::get('/contacto', [SiteController::class, 'contact'])->name('contact');
