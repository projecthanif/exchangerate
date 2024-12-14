<?php
declare(strict_types=1);

namespace Projecthanif\ExchangeRate;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Projecthanif\ExchangeRate\Exceptions\CurrencyException;
use Projecthanif\ExchangeRate\Traits\ExchangeRateHelper;


class ExchangeRate
{
    use ExchangeRateHelper;

    /**
     * Currency Code
     * Default value set to United States Currency Code
     * @param string $currencyCode
     * */
    private string $currencyCode = 'USD';

    /**
     * Currency Code
     * Default value set to Europe's Currency Code
     * @param string $pairWithCurrencyCode
     * */
    private string $pairWithCurrencyCode = 'EUR';


    private string $exchangeRateUrl;


    private array $data;

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
     * @param string $currencyCode
     * @return ExchangeRate
     * @throws CurrencyException
     */
    public function standardResponse(string $currencyCode): self
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
        $this->data = $data;

        return $this;
    }

    /**
     * @param string $currencyCode
     * @param string|null $pairWithCurrencyCode
     * @return ExchangeRate
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
     * @param float $amount
     * @param string $currencyCode
     * @param string $pairWithCurrencyCode
     * @return ExchangeRate
     * @throws CurrencyException
     */
    public function pairConversionWithAmount(float $amount, string $currencyCode, string $pairWithCurrencyCode): self
    {

        $this->conversionFromTo($currencyCode, $pairWithCurrencyCode);

        $response = Http::get("$this->exchangeRateUrl/pair/$this->currencyCode/$this->pairWithCurrencyCode/$amount");

        if ($response->failed()) {
            throw new CurrencyException("Failed to fetch currency data.");
        }

        $data = $response->json();

        if (isset($data['error']) && $data['result'] === "error") {
            throw new CurrencyException($data['message'] ?? 'Unknown error');
        }

        $this->data;
        return $this;
    }

}