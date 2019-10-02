<?php
Route::post('/webhooks/optimizely', [\App\Http\Controllers\OptimizelyController::class, 'webhook']);
