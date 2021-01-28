<?php

declare(strict_types=1);

namespace App\Twig;

use App\Entity\Vat;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AmountExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('amountEUR', [$this, 'amountEUR']),
            new TwigFilter('amountUSD', [$this, 'amountUSD']),
            new TwigFilter('amountEURWithVat', [$this, 'amountEURWithVat']),
            new TwigFilter('amountUSDWithVat', [$this, 'amountUSDWithVat']),
        ];
    }

    public function amountEUR(int $price): string
    {
        return number_format($price / 100, 2, ',', ' ') . ' €';
    }

    public function amountUSD(int $price): string
    {
        return '$' . number_format($price / 100, 2, '.', ',');
    }

    public function amountEURWithVat(int $price, Vat $vat): string
    {
        $formatedUnitPrice = $price / 100;
        $vatAmount = $formatedUnitPrice * $vat->getValue() / 100;
        $amount = $formatedUnitPrice + $vatAmount;
        return number_format($amount, 2, ',', ' ') . ' €';
    }

    public function amountUSDWithVat(int $price, Vat $vat): string
    {
        $formatedUnitPrice = $price / 100;
        $vatAmount = $formatedUnitPrice * $vat->getValue() / 100;
        $amount = $formatedUnitPrice + $vatAmount;
        return '$' . number_format($amount, 2, '.', ',');
    }
}
