<?php

declare(strict_types=1);

namespace Theatrical;

final class Performance
{
    /**
     * @var string
     */
    public string $play_id;

    /**
     * @var int
     */
    public int $audience;

    public \Theatrical\Play $play;

    public function __construct(string $play_id, int $audience)
    {
        $this->play_id  = $play_id;
        $this->audience = $audience;
    }
}
