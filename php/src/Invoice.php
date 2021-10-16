<?php

declare(strict_types=1);

namespace Theatrical;

final class Invoice
{
    public string $customer;

    /**
     * @var Performance[]
     */
    public array $performances;

    public function __construct(string $customer, array $performances)
    {
        $this->customer     = $customer;
        $this->performances = $performances;
    }
}
