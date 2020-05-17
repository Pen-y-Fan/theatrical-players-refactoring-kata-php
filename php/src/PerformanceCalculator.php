<?php

declare(strict_types=1);

namespace Theatrical;


class PerformanceCalculator
{
    /**
     * @var Performance
     */
    private $performance;

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
}