<?php
return [
    'disk' => env (OPTIMIZELY_DISK),
    'path' => env('OPTIMIZELY_DATAFILE_PATH'),
    'webhook_secret' => env('OPTIMIZELY_WEBHOOK_SECRET'),
];
