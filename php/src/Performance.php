<?php

declare(strict_types=1);

namespace Theatrical;

final class Performance
{
    public \Theatrical\Play $play;

    public function __construct(
        public string $play_id,
        public int $audience
    ) {
    }
}
