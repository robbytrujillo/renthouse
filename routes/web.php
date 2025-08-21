<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

// fokus ke end point, pindahkan ke routes/api.php
// require __DIR__.'/auth.php';