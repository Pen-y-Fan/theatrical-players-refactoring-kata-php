<?php

declare(strict_types=1);

namespace Theatrical;

class ComedyCalculator extends PerformanceCalculator
{
    public function getAmount(): int
    {
        $result = 30000;
        if ($this->performance->audience > 20) {
            $result += 10000 + 500 * ($this->performance->audience - 20);
        }
        $result += 300 * $this->performance->audience;
        return $result;
    }

    /**
     * getVolumeCredits() from parent PerformanceCalculator
     */
    public function volumeCredits(): int
    {
        return $this->getVolumeCredits() + (int) floor($this->performance->audience / 5);
    }
}
