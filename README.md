## Overview
A simple Laravel package used for interacting with [ExchangeRate Api](https://exchangerate-api.com/).

## Installation
You can install the package via Composer:
```bash
composer require projecthanif/exchangerate
```

## Api

This Exchange rate package only supports [ExchangeRate Api](https://exchangerate-api.com/)

## Configuration

You should publish the package's config file using the following command:

```bash 
php artisan vendor:publish --provider="Projecthanif\ExchangeRate\ExchangeRateServiceProvider"
```

You would need to get your api key from [ExchangeRate](https://exchangerate-api.com/)

```env
ExchangeRate_API_KEY={Your-API-Key-Here}
```

## Usage

### Method

#### To get Supported Currencies

```php
use Projecthanif/ExchangeRate/ExchangeRate;

$exchangeRates = app(ExchangeRate::class);

$supportedCurrencies = $exchangeRates->getSupportedCodes();

```

#### Exchange Rates

##### To get Exchange Rate of a currency relative to another

It takes a string which will be a valid currency code

```php
use Projecthanif/ExchangeRate/ExchangeRate;

$exchangeRates = app(ExchangeRate::class);

$rates = $exchangeRates->standardResponse("NGN")->getConversionRates();

```


##### To get a particular one out of the above one

It takes a string which will be a valid currency code

```php
use Projecthanif/ExchangeRate/ExchangeRate;

$exchangeRates = app(ExchangeRate::class);

$supportedCurrencies = $exchangeRates->standardResponse("NGN")->getConversionRates("USD");

```

