<?php

declare(strict_types=1);

namespace Theatrical;

use Error;

class PerformanceCalculator
{
    public \Theatrical\Play $play;

    protected \Theatrical\Performance $performance;

    public function __construct(Performance $performance, Play $play)
    {
        $this->performance = $performance;
        $this->play        = $play;
    }

    /**
     * @noRector \Rector\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector
     */
    public function getAmount(): int
    {
        throw new Error('subclass responsibility');
    }

    public function volumeCredits(): int
    {
        return $this->getVolumeCredits();
    }

    protected function getVolumeCredits(): int
    {
        return max($this->performance->audience - 30, 0);
    }
}
