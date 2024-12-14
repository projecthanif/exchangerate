<?php
return [
    'exchangerate' => [
        'url' => env('EXCHANGERATE_URL', "https://v6.exchangerate-api.com/v6"),
        'api_key' => env('EXCHANGERATE_API_KEY', ""),
    ],
];