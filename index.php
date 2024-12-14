<?php
declare(strict_types=1);

require __DIR__ . "/vendor/autoload.php";

use Dotenv\Dotenv;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Facade;
use Projecthanif\CurrencyConverter\CurrencyConverter;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$app = new Container();

$app->singleton('http', function ($app) {
    return new \Illuminate\Http\Client\Factory();
});

Facade::setFacadeApplication($app);

$check = new CurrencyConverter();

$res = $check
    ->currencyFromTo('USD', "NGN");

var_dump($res);