<?php

return [
    'manifest' => 'public/build/manifest.json',

    'hot_file' => 'public/hot',

    'build_path' => 'build',

    'dev_server' => [
        'url' => env('VITE_DEV_SERVER_URL'),
        'ping_timeout' => 2000,
    ],
];
