<?php

declare(strict_types=1);

namespace Theatrical;

use Error;

class ComedyCalculator extends PerformanceCalculator
{
    public function getAmount(): int
    {
        switch ($this->play->type) {
            case "tragedy":
                Throw new Error("Bad Thing");
            case "comedy":
                $result = 30000;
                if ($this->performance->audience > 20) {
                    $result += 10000 + 500 * ($this->performance->audience - 20);
                }
                $result += 300 * $this->performance->audience;
                return $result;
            default:
                Throw new Error("unknown type: {$this->play->type}");
        }
    }
}