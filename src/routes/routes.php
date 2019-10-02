<?php
Route::post('/webhooks/optimizely', [\LeroyMerlin\Optimizely\Http\Controllers\OptimizelyController::class, 'webhook']);
