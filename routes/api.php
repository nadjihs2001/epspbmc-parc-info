<?php

use App\Http\Controllers\AI\AiController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::post('/ai/diagnose', [AiController::class, 'diagnose'])->name('ai.diagnose');
});
