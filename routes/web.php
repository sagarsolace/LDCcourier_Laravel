<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| LDC Courier – static frontend routes (WordPress URL structure preserved)
|--------------------------------------------------------------------------
*/

Route::view('/', 'website.home')->name('home');
Route::view('/about-us-2', 'website.about-us-2')->name('about-us-2');
Route::view('/our-services', 'website.our-services')->name('our-services');
Route::view('/contact-us', 'website.contact-us')->name('contact-us');
Route::view('/carpet-flooring-transport', 'website.carpet-flooring-transport')->name('carpet-flooring-transport');
Route::view('/privacy-policy-2', 'website.privacy-policy-2')->name('privacy-policy-2');
Route::view('/terms-conditions', 'website.terms-conditions')->name('terms-conditions');
Route::view('/cookie-policy', 'website.cookie-policy')->name('cookie-policy');

Route::view('/blog', 'website.blog', ['posts' => [
    [
        'title' => 'Blog',
        'slug' => 'blog',
        'excerpt' => 'Latest news and updates from LDC Courier.',
        'date' => '2026-01-31',
        'url' => '/blog',
    ],
]])->name('blog');

// Common aliases. Apache's public/.htaccess handles trailing slash removal.
Route::redirect('/about-us', '/about-us-2', 301);
Route::redirect('/services', '/our-services', 301);
