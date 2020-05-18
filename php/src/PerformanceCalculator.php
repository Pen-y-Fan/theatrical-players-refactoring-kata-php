<?php

declare(strict_types=1);

namespace Theatrical;

use Error;

class PerformanceCalculator
{
    /**
     * @var Play
     */
    public $play;

    /**
     * @var Performance
     */
    protected $performance;

    /**
     * PerformanceCalculator constructor.
     */
    public function __construct(Performance $performance, Play $play)
    {
        $this->performance = $performance;
        $this->play = $play;
    }

    public function getAmount(): int
    {
        throw new Error('subclass responsibility');
    }

    public function volumeCredits(): int
    {
        return (int) $this->getVolumeCredits();
    }

    protected function getVolumeCredits(): int
    {
        return (int) max($this->performance->audience - 30, 0);
    }
}
