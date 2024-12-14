<?php
return [

    /*
    |----------------------------------------------------------------------|
    | Exchange Rate API Configuration                                      |
    |----------------------------------------------------------------------|
    |                                                                      |
    | Here you may specify the settings for the Exchange Rate API service. |
    |
    */

    'exchangerate' => [
        'url' => env('EXCHANGERATE_URL', "https://v6.exchangerate-api.com/v6"),
        'api_key' => env('EXCHANGERATE_API_KEY', ""),
    ],
];