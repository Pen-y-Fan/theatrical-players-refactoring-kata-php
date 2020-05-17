<?php

declare(strict_types=1);

namespace Theatrical;

class Invoice
{
    public $customer;

    public $performances;

    public function __construct($customer, $performances)
    {
        $this->customer = $customer;
        $this->performances = $performances;
    }
}
