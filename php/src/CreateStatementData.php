<?php

declare(strict_types=1);

namespace Theatrical;

use Error;

final class CreateStatementData
{
    public string $customer = '';

    /**
     * @var Performance[]
     */
    public mixed $performances = [];

    public int $totalVolumeCredits = 0;

    public int $totalAmount = 0;

    /**
     * @param Play[] $plays
     */
    public function __construct(
        private Invoice $invoice,
        private array $plays
    ) {
    }

    public function createStatementData(): self
    {
        $this->customer           = $this->invoice->customer;
        $this->performances       = $this->enrichPerformance();
        $this->totalVolumeCredits = $this->totalVolumeCredits();
        $this->totalAmount        = $this->totalAmount($this->performances);
        return $this;
    }

    private function playFor(Performance $performance): Play
    {
        return $this->plays[$performance->play_id];
    }

    private function totalVolumeCredits(): int
    {
        return array_reduce($this->performances, fn ($total, $performance): int => $total + $performance->volumeCredit, 0);
    }

    private function totalAmount(array $performances): int
    {
        return array_reduce($performances, fn ($total, $performance): int => $total + $performance->amount, 0);
    }

    /**
     * @return Performance[]
     */
    private function enrichPerformance(): array
    {
        return array_map(function (Performance $performance): Performance {
            $calculator = $this->createPerformanceCalculator($performance, $this->playFor($performance));
            $result = clone $performance;
            $result->play = clone $calculator->play;
            $result->amount = $calculator->getAmount();
            $result->volumeCredit = $calculator->volumeCredits();
            return $result;
        }, $this->invoice->performances);
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
