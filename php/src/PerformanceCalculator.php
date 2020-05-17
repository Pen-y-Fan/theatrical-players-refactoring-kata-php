<?php

declare(strict_types=1);

namespace Theatrical;

use Error;

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

    public function getAmount()
    {
        switch ($this->play->type) {
            case "tragedy":
                $result = 40000;
                if ($this->performance->audience > 30) {
                    $result += 1000 * ($this->performance->audience - 30);
                }
                break;
            case "comedy":
                $result = 30000;
                if ($this->performance->audience > 20) {
                    $result += 10000 + 500 * ($this->performance->audience - 20);
                }
                $result += 300 * $this->performance->audience;
                break;
            default:
                throw new Error("unknown type: {$this->play->type}");
        }
        return $result;
    }
}