<?php

declare(strict_types=1);

namespace Theatrical;

use Error;
use NumberFormatter;

class StatementPrinter
{
    /**
     * @var Play
     */
    private $plays;

    public function print(Invoice $invoice, array $plays)
    {
        $this->plays = $plays;
        $totalAmount = 0;

        $result = 'Statement for ' . $invoice->customer . PHP_EOL;
        foreach ($invoice->performances as $performance) {
            $result .= "  {$this->playFor($performance)->name}: {$this->usd($this->amountFor($performance))} ({$performance->audience} seats)" . PHP_EOL;
            $totalAmount += $this->amountFor($performance);
        }
        $volumeCredits = 0;
        foreach ($invoice->performances as $performance) {
            $volumeCredits += $this->volumeCreditsFor($performance);
        }
        $finalTotal = $this->usd($totalAmount);
        $result .= "Amount owed is $finalTotal" . PHP_EOL;
        $result .= "You earned $volumeCredits credits" . PHP_EOL;
        return $result;
    }

    private function amountFor(Performance $performance): int
    {
        switch ($this->playFor($performance)->type) {
            case "tragedy":
                $result = 40000;
                if ($performance->audience > 30) {
                    $result += 1000 * ($performance->audience - 30);
                }
                break;

            case "comedy":
                $result = 30000;
                if ($performance->audience > 20) {
                    $result += 10000 + 500 * ($performance->audience - 20);
                }
                $result += 300 * $performance->audience;
                break;

            default:
                throw new Error("Unknown type: {$this->playFor($performance)->type}");
        }
        return (int)$result;
    }

    private function playFor(Performance $performance): Play
    {
        return $this->plays[$performance->play_id];
    }

    private function volumeCreditsFor(Performance $performance): int
    {
        $result = max($performance->audience - 30, 0);
        if ($this->playFor($performance)->type == 'comedy') {
            $result += floor($performance->audience / 5);
        }
        return (int)$result;
    }

    private function usd(float $value): string
    {
        return (new NumberFormatter('en_US', NumberFormatter::CURRENCY))
            ->formatCurrency($value / 100, 'USD');
    }
}