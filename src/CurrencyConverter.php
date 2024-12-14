<?php
declare(strict_types=1);

namespace Projecthanif\CurrencyConverter;

use Illuminate\Support\Facades\Http;

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

    public function standardResponse(): mixed
    {
        try {
            $init = Http::get(
                env('EXCHANGERATE_URL') . env('EXCHANGERATE_API_KEY') . "/latest/{$this->currencyCode}"
            );

            $response = $init->json();

            if ($response['result'] === "error") {
                throw new \Exception($response['error-type']);
            }

            return $response;
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }

    }

    public function conversionFromTo(): mixed
    {
        try {

            $init = Http::get(
                env('EXCHANGERATE_URL') . env('EXCHANGERATE_API_KEY') .
                "/pair/{$this->currencyCode}/{$this->pairWithCurrencyCode}"
            );

            $response = $init->json();

            if ($response['result'] === "error") {
                throw new \Exception($response['error-type']);
            }

            return $response;
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function pairConversionWithAmount(float $amount): mixed
    {
        try {

            $init = Http::get(
                env('EXCHANGERATE_URL') . env('EXCHANGERATE_API_KEY') .
                "/pair/{$this->currencyCode}/{$this->pairWithCurrencyCode}/$amount"
            );

            $response = $init->json();

            if ($response['result'] === "error") {
                throw new \Exception($response['error-type']);
            }

            return $response;
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function currencyCode(string $currencyCode): self
    {
        $this->currencyCode = $currencyCode;
        return $this;
    }

    public function currencyFromTo(string $currencyCode, ?string $pairWithCurrencyCode = null): self
    {
        $this->currencyCode = $currencyCode;
        $this->pairWithCurrencyCode = $pairWithCurrencyCode ?? $this->pairWithCurrencyCode;
        return $this;
    }
}