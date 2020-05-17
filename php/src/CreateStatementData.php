<?php

declare(strict_types=1);

namespace Theatrical;

use Error;
use stdClass;

class CreateStatementData
{
    public function createStatementData(Invoice $invoice, array $plays): stdClass
    {
        $statementData = new stdClass();
        $statementData->customer = $invoice->customer;
        $statementData->performances = $this->enrichPerformance($invoice->performances, $plays);
        $statementData->totalVolumeCredits = $this->totalVolumeCredits($statementData->performances);
        $statementData->totalAmount = $this->totalAmount($statementData->performances);
        return $statementData;
    }

    private function playFor(Performance $performance, array $plays): Play
    {
        return $plays[$performance->play_id];
    }

    private function totalVolumeCredits(array $performances): int
    {
        return array_reduce($performances, function ($total, $performance) {
            return $total + $performance->volumeCredit;
        }, 0);
    }

    private function totalAmount(array $performances): int
    {
        return array_reduce($performances, function ($total, $performance) {
            return $total + $performance->amount;
        }, 0);
    }

    private function enrichPerformance(array $performances, array $plays): array
    {
        return array_map(function ($performance) use ($plays) {
            $calculator = $this->createPerformanceCalculator($performance, $this->playFor($performance, $plays));
            $result = clone $performance;
            $result->play = clone $calculator->play;
            $result->amount = $calculator->getAmount();
            $result->volumeCredit = $calculator->volumeCredits();
            return $result;
        }, $performances);
    }

    /**
     * @param Performance $performance
     * @param Play $play
     * @return PerformanceCalculator
     */
    private function createPerformanceCalculator(Performance $performance, Play $play): PerformanceCalculator
    {
        switch($play->type) {
            case "tragedy": return new TragedyCalculator($performance, $play);
            case "comedy" : return new ComedyCalculator($performance, $play);
            default:
                throw new Error("unknown type: {$play->type}");
        }
    }
}
