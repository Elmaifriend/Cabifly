<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DemoController;


Route::get('/', function () {
    return view('app');

    // Para la Raspberry Pi (GET)
    Route::get('/start-demo', [DemoController::class, 'checkDemoStatus']);

    // Para activar desde el formulario (POST)
    Route::post('/activate-demo', [DemoController::class, 'activateDemo']);
});
