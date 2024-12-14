<?php
declare(strict_types=1);

namespace Projecthanif\ExchangeRate;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Projecthanif\ExchangeRate\Exceptions\CurrencyException;


class CurrencyConverter
{
    /*
     * Currency Code
     * Default value set to United States Currency Code
     * @var string
     * **/
    private string $currencyCode = 'USD';

    /*
     * Currency Code
     * @var string
     * **/
    private string $pairWithCurrencyCode = 'EUR';


    private string $exchangeRateUrl;

    /**
     * @throws CurrencyException
     */
    public function __construct(Config $config)
    {
        $url = $config::get('projecthanif-exchangerate.exchangerate.url');
        $api_key = $config::get('projecthanif-exchangerate.exchangerate.api_key');

        if ($url === null || $api_key === null) {
            throw new CurrencyException('Configs not provided');
        }
        $this->exchangeRateUrl = rtrim($url, "/") . "/" . $api_key;
    }

    /**
     * @throws CurrencyException
     */
    public function standardResponse(string $currencyCode): mixed
    {
        $this->validateCurrencyCode($currencyCode);

        $response = Http::get($this->exchangeRateUrl . "/latest/" . $this->currencyCode);

        if ($response->failed()) {
            throw new CurrencyException("Failed to fetch currency data.");
        }

        $data = $response->json();
        if (isset($data['error']) && $data['result'] === "error") {
            throw new CurrencyException($data['message'] ?? 'Unknown error');
        }
        return $data;

    }

    /**
     * @throws CurrencyException
     */
    public function conversionFromTo(string $currencyCode, ?string $pairWithCurrencyCode = null): mixed
    {
        $this->validateCurrencyFromTo($currencyCode, $pairWithCurrencyCode);

        $response = Http::get("$this->exchangeRateUrl/pair/$this->currencyCode/$this->pairWithCurrencyCode");

        if ($response->failed()) {
            throw new CurrencyException("Failed to fetch currency data.");
        }

        $data = $response->json();

        if (isset($data['error']) && $data['result'] === "error") {
            throw new CurrencyException($data['message'] ?? 'Unknown error');
        }
        return $data;
    }

    /**
     * @throws CurrencyException
     */
    public function pairConversionWithAmount(float $amount, string $currencyCode, string $pairWithCurrencyCode): mixed
    {

        $response = Http::get("$this->exchangeRateUrl/pair/$this->currencyCode/$this->pairWithCurrencyCode/$amount");

        if ($response->failed()) {
            throw new CurrencyException("Failed to fetch currency data.");
        }

        $data = $response->json();

        if (isset($data['error']) && $data['result'] === "error") {
            throw new CurrencyException($data['message'] ?? 'Unknown error');
        }


        return $data;
    }

    /**
     * @throws CurrencyException
     */
    private function validateCurrencyCode(string $currencyCode): void
    {
        if (strlen($currencyCode) !== 3) {
            throw new CurrencyException("Currency Code must be 3 characters long.");
        }

        $this->currencyCode = strtoupper($currencyCode);
    }

    /**
     * @throws CurrencyException
     */
    private function validateCurrencyFromTo(string $currencyCode, ?string $pairWithCurrencyCode = null): void
    {
        $this->validateCurrencyCode($currencyCode);

        if ($pairWithCurrencyCode !== null) {
            if (strlen($pairWithCurrencyCode) !== 3) {
                throw new CurrencyException("Pair Currency Code must be 3 characters long.");
            }
            $this->pairWithCurrencyCode = strtoupper($pairWithCurrencyCode);
        }
    }
}