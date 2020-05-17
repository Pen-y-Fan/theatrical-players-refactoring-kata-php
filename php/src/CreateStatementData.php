<?php

declare(strict_types=1);

namespace Theatrical;

use Error;
use NumberFormatter;
use stdClass;

class CreateStatementData
{
    public function createStatementData(Invoice $invoice, array $plays): stdClass
    {
        $statementData = new stdClass();
        $statementData->customer = $invoice->customer;
        $statementData->performances = $this->enrichPerformance($invoice->performances, $plays);
        $statementData->totalVolumeCredits = $this->totalVolumeCredits($statementData->performances);
        $statementData->totalAmount = $this->usd($this->totalAmount($statementData->performances));
        return $statementData;
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

    private function playFor(Performance $performance, array $plays): Play
    {
        return $plays[$performance->play_id];
    }

    private function volumeCreditFor($performance): int
    {
        $result = max($performance->audience - 30, 0);
        if ($performance->play->type == 'comedy') {
            $result += floor($performance->audience / 5);
        }
        return (int)$result;
    }

    private function usd(float $value): string
    {
        return (new NumberFormatter('en_US', NumberFormatter::CURRENCY))
            ->formatCurrency($value / 100, 'USD');
    }

    private function totalVolumeCredits($performances): int
    {
        return array_reduce($performances, function ($total, $performance){
            return $total + $performance->volumeCredit;
        },0);
    }

    private function totalAmount($performances): int
    {
        return array_reduce($performances, function ($total, $performance){
            return $total + $performance->amount;
        },0);
    }

    private function enrichPerformance($performances, $plays)
    {
        return array_map(function($performance) use ($plays) {
            $result = clone $performance;
            $result->play = clone $this->playFor($result, $plays);
            $result->amount = $this->amountFor($result);
            $result->volumeCredit = $this->volumeCreditFor($result);
            return $result;
        }, $performances);
    }
}