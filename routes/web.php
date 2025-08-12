<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('Welcome');

Route::get('/reverb', function () {
    event(new \App\Events\PublicEvent('This is a public event message'));
    return response()->json(['status' => 'Event dispatched']);
});