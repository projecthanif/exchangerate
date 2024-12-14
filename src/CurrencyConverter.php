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

    public function standardResponse(string $currencyCode): mixed
    {
        $this->currencyCode($currencyCode);
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

    public function conversionFromTo(string $currencyCode, ?string $pairWithCurrencyCode = null): mixed
    {
        $this->currencyFromTo($currencyCode, $pairWithCurrencyCode);
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

    public function pairConversionWithAmount(float $amount, string $currencyCode, string $pairWithCurrencyCode): mixed
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

    private function currencyCode(string $currencyCode): void
    {
        $this->currencyCode = $currencyCode;
    }

    private function currencyFromTo(string $currencyCode, ?string $pairWithCurrencyCode = null): void
    {
        try {
            if (strlen($currencyCode) !== 3) {
                throw new \Exception("Currency Code must be 3 digits.");
            }

            if ($pairWithCurrencyCode !== null && strlen($pairWithCurrencyCode) !== 3) {
                throw new \Exception("Currency Code must be 3 digits.");
            }

            $this->currencyCode = $currencyCode;
            $this->pairWithCurrencyCode = $pairWithCurrencyCode ?? $this->pairWithCurrencyCode;
            return;

        } catch (\Exception $exception) {
            echo $exception->getMessage();
            return;
        }
    }
}