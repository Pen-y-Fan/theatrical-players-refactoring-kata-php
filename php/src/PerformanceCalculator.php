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
     * PerformanceCalculator constructor.
     * @param Performance $performance
     */
    public function __construct(Performance $performance)
    {

        $this->performance = $performance;
    }
}