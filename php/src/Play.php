<?php

declare(strict_types=1);

namespace Theatrical;

final class Play
{
    public string $name;

    public string $type;

    public function __construct(string $name, string $type)
    {
        $this->name = $name;
        $this->type = $type;
    }

    public function __toString(): string
    {
        return $this->name . ' : ' . $this->type;
    }
}
