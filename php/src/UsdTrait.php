<?php

declare(strict_types=1);

namespace Theatrical;

use NumberFormatter;

trait UsdTrait
{
    private function usd(float $value): string
    {
        return (new NumberFormatter('en_US', NumberFormatter::CURRENCY))
            ->formatCurrency($value / 100, 'USD');
    }
}
