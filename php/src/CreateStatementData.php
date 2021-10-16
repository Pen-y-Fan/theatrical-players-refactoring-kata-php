<?php

declare(strict_types=1);

namespace Theatrical;

use Error;

final class CreateStatementData
{
    public string $customer;

    /**
     * @var mixed[]|mixed
     */
    public mixed $performances;

    public int $totalVolumeCredits;

    public int $totalAmount;

    public function createStatementData(Invoice $invoice, array $plays): self
    {
        $this->customer           = $invoice->customer;
        $this->performances       = $this->enrichPerformance($invoice->performances, $plays);
        $this->totalVolumeCredits = $this->totalVolumeCredits($this->performances);
        $this->totalAmount        = $this->totalAmount($this->performances);
        return $this;
    }

    private function playFor(Performance $performance, array $plays): Play
    {
        return $plays[$performance->play_id];
    }

    private function totalVolumeCredits(array $performances): int
    {
        return array_reduce($performances, fn ($total, $performance): int => $total + $performance->volumeCredit, 0);
    }

    private function totalAmount(array $performances): int
    {
        return array_reduce($performances, fn ($total, $performance): int => $total + $performance->amount, 0);
    }

    /**
     * @return mixed[]
     */
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

    private function createPerformanceCalculator(Performance $performance, Play $play): ComedyCalculator|TragedyCalculator
    {
        return match ($play->type) {
            'tragedy' => new TragedyCalculator($performance, $play),
            'comedy'  => new ComedyCalculator($performance, $play),
            default   => throw new Error(sprintf('unknown type: %s', $play->type)),
        };
    }
}
