<?php

declare(strict_types=1);

namespace Theatrical;

final class Play
{
    /**
     * @var string
     */
    public string $name;

    /**
     * @var string
     */
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
