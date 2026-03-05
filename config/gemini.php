<?php
// config/gemini.php
return [
    'api_key' => env('GEMINI_API_KEY'),
    'request_options' => [
        'timeout' => 180, // Aquí es donde lo pones
        'connect_timeout' => 10,
    ],
];