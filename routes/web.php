<?php

use App\Http\Controllers\ContactFormController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\WpAdminAjaxController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| LDC Courier – static frontend routes (WordPress URL structure preserved)
|--------------------------------------------------------------------------
*/

Route::view('/', 'website.home')->middleware('cache.headers:public;max_age=600;etag')->name('home');
Route::view('/about-us-2', 'website.about-us-2')->middleware('cache.headers:public;max_age=600;etag')->name('about-us-2');
Route::view('/our-services', 'website.our-services')->middleware('cache.headers:public;max_age=600;etag')->name('our-services');
Route::view('/contact-us', 'website.contact-us')->middleware('cache.headers:public;max_age=300;etag')->name('contact-us');
Route::post('/contact-us', [ContactFormController::class, 'store'])->name('contact-us.store');
Route::post('/wp-admin/admin-ajax.php', [WpAdminAjaxController::class, 'handle'])->name('wp.admin.ajax');
Route::post('/ajax/form-handler', [WpAdminAjaxController::class, 'handle'])->name('ajax.form.handler');
Route::view('/carpet-flooring-transport', 'website.carpet-flooring-transport')->middleware('cache.headers:public;max_age=600;etag')->name('carpet-flooring-transport');
Route::view('/privacy-policy-2', 'website.privacy-policy-2')->middleware('cache.headers:public;max_age=600;etag')->name('privacy-policy-2');
Route::view('/terms-conditions', 'website.terms-conditions')->middleware('cache.headers:public;max_age=600;etag')->name('terms-conditions');
Route::view('/cookie-policy', 'website.cookie-policy')->middleware('cache.headers:public;max_age=600;etag')->name('cookie-policy');

Route::get('/blog', [BlogController::class, 'index'])->middleware('cache.headers:public;max_age=600;etag')->name('blog');

// Common aliases. Apache's public/.htaccess handles trailing slash removal.
Route::redirect('/about-us', '/about-us-2', 301);
Route::redirect('/services', '/our-services', 301);
