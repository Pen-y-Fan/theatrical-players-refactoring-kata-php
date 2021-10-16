<?php

declare(strict_types=1);

namespace Theatrical;

use Error;

class PerformanceCalculator
{
    public function __construct(
        protected Performance $performance,
        public Play $play
    ) {
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
