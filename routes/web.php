<?php

use App\Http\Controllers\VideoConverter;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('app');
});

Route::post('/converter', [VideoConverter::class, 'converter'])->name('converter');
