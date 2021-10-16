<?php

declare(strict_types=1);

namespace Theatrical;

final class Invoice
{
    /**
     * @param Performance[] $performances
     */
    public function __construct(
        public string $customer,
        public array $performances
    ) {
    }
}
