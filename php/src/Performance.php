<?php

declare(strict_types=1);

namespace Theatrical;

final class Performance
{
    public int $amount = 0;

    public int $volumeCredit = 0;

    public Play $play;

    public function __construct(
        public string $play_id,
        public int $audience
    ) {
    }
}
