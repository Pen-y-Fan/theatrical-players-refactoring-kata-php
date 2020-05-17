<?php

declare(strict_types=1);

namespace Theatrical;

use Error;
use NumberFormatter;
use stdClass;

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

    public function print(Invoice $invoice, array $plays): string
    {
        $this->invoice = $invoice;
        $this->plays = $plays;
        $statementData = new stdClass();
        $statementData->customer = $this->invoice->customer;
        $statementData->totalVolumeCredits = $this->totalVolumeCredits();
        $statementData->performances = array_map([$this, "enrichPerformance"], $this->invoice->performances);
        $statementData->totalAmount = $this->usd($this->totalAmount($statementData->performances));
        return $this->renderPlainText($statementData);
    }

    public function renderPlainText(stdClass $data): string
    {
        $result = "Statement for {$data->customer}" . PHP_EOL;
        foreach ($data->performances as $performance) {
            $result .= "  {$performance->play->name}: {$this->usd($performance->amount)}";
            $result .= " ({$performance->audience} seats)" . PHP_EOL;
        }
        $result .= "Amount owed is {$data->totalAmount}" . PHP_EOL;
        $result .= "You earned {$data->totalVolumeCredits} credits" . PHP_EOL;
        return $result;
    }

    private function amountFor($performance): int
    {
        switch ($performance->play->type) {
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
                throw new Error("Unknown type: {$performance->play->type}");
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

    private function totalAmount($performances): int
    {
        $result = 0;
        foreach ($performances as $performance) {
            $result += $performance->amount;
        }
        return $result;
    }

    private function enrichPerformance($performance)
    {
        $result = clone $performance;
        $result->play = clone $this->playFor($result);
        $result->amount = $this->amountFor($result);
        return $result;
    }
}
