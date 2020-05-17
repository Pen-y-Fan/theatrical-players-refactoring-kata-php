<?php

declare(strict_types=1);

namespace Theatrical;

use Error;

class TragedyCalculator extends PerformanceCalculator
{
    public function getAmount(): int
    {
        switch ($this->play->type) {
            case "tragedy":
                $result = 40000;
                if ($this->performance->audience > 30) {
                    $result += 1000 * ($this->performance->audience - 30);
                }
                return $result;
            case "comedy":
                Throw new Error("Bad Thing");
                break;
            default:
                Throw new Error("unknown type: {$this->play->type}");
        }
    }
}