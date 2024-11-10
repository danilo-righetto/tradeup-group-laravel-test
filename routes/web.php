<?php

use Illuminate\Support\Facades\Route;

Route::get('/health/live', function () {
    return 'ok';
});
