<?php
declare(strict_types=1);

namespace Projecthanif\ExchangeRate\Traits;

use Projecthanif\ExchangeRate\Exceptions\CurrencyException;

trait ExchangeRateHelper
{

    /**
     * @param null|string $currencyCode
     * @return mixed
     * @throws CurrencyException
     */
    public function getConversionRate(?string $currencyCode = null): mixed
    {
        $data = $this->data;
        if ($currencyCode !== null) {
            return $data['conversion_rates'][$currencyCode];
        }
        return $data['conversion_rates'];
    }

    /**
     * @param string $currencyCode
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
     * @param string|null $pairWithCurrencyCode
     * @param string $currencyCode
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