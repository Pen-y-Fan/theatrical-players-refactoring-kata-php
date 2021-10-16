<?php

declare(strict_types=1);

namespace Theatrical;

final class Play implements \Stringable
{
    public function __construct(
        public string $name,
        public string $type
    ) {
    }

    public function __toString(): string
    {
        return $this->name . ' : ' . $this->type;
    }
}
