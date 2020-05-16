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
    /**
     * @var Invoice
     */
    private $invoice;

    public function print(Invoice $invoice, array $plays)
    {
        $this->invoice = $invoice;
        $this->plays = $plays;

        $result = "Statement for {$invoice->customer}" . PHP_EOL;
        foreach ($this->invoice->performances as $performance) {
            $result .= "  {$this->playFor($performance)->name}: {$this->usd($this->amountFor($performance))}";
            $result .= " ({$performance->audience} seats)" . PHP_EOL;
        }
        $result .= "Amount owed is {$this->usd($this->totalAmount())}" . PHP_EOL;
        $result .= "You earned {$this->totalVolumeCredits()} credits" . PHP_EOL;
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

    private function totalVolumeCredits(): int
    {
        $result = 0;
        foreach ($this->invoice->performances as $performance) {
            $result += $this->volumeCreditsFor($performance);
        }
        return $result;
    }

    private function totalAmount(): int
    {
        $result = 0;
        foreach ($this->invoice->performances as $performance) {
            $result += $this->amountFor($performance);
        }
        return $result;
    }
}