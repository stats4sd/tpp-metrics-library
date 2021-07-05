<?php

use Illuminate\Support\Facades\Route;

/**
 * No seperate Front-end
 *
 * Use these routes for platforms that only use the
 * Laravel Backpack interface and do not require a seperate
 * front-end.
 *
 * The routes here will automatically redirect any users to the
 * Backpack admin root. Logins will still be handled with Laravel / Breeze.
 */

Route::get('/', function () {
    return redirect(config('backpack.base.route_prefix'));
});

Route::get(config('backpack.base.route_prefix') . '/login', function () {
    return redirect('login');
});

/**
 * For platforms that *do* require a seperate front-end and
 * admin panel, use the routes below. This will not automatically redirect.
 * The groups are to seperate out front-end pages that are public vs
 * front-end pages that require authentication.
 */

// Route::get('/', function () {
//     return view('welcome');
// })->name('home');

// Route::group([
//     'middleware' => ['web', 'auth'],
// ], function () {
//     Route::view('dashboard')->name('dashboard');
// });

require __DIR__.'/auth.php';
