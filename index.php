<?php
declare(strict_types=1);

require __DIR__ . "/vendor/autoload.php";

use Dotenv\Dotenv;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Facade;
use Projecthanif\ExchangeRate\ExchangeRate;
use Illuminate\Support\Facades\Config;
use Illuminate\Config\Repository as ConfigRepository;


$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$app = new Container();

$app->singleton('http', function ($app) {
    return new \Illuminate\Http\Client\Factory();
});

$config = new ConfigRepository();

$configFiles = glob(__DIR__ . '/config/*.php');

foreach ($configFiles as $file) {
    $configName = basename($file, '.php');
    $config->set($configName, require $file);
}

$app->singleton('config', function () use ($config) {
    return $config;
});


Facade::setFacadeApplication($app);

try {
    $converter = new ExchangeRate(new Config());
    $response = $converter->standardResponse('NGN')->getConversionRate();
    dd($response);
} catch (\Projecthanif\ExchangeRate\Exceptions\CurrencyException $e) {
    dd($e->getMessage());
}

