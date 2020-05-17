<?php

declare(strict_types=1);

namespace Theatrical;

use Error;

class PerformanceCalculator
{
    /**
     * @var Performance
     */
    protected $performance;

    /**
     * @var Play
     */
    public $play;

    /**
     * PerformanceCalculator constructor.
     * @param Performance $performance
     * @param Play $play
     */
    public function __construct(Performance $performance, Play $play)
    {
        $this->performance = $performance;
        $this->play = $play;
    }

    public function getAmount(): int
    {
        Throw new Error('subclass responsibility');
    }

    public function volumeCredit(): int
    {
        $result = max($this->performance->audience - 30, 0);
        if ($this->play->type === 'comedy') {
            $result += floor($this->performance->audience / 5);
        }
        return (int) $result;
    }

}