<?php
declare(strict_types=1);

namespace Theatrical;

class Performance 
{
    public $play_id;
    public $audience;

    public function __construct($play_id, $audience)
    {
        $this->play_id = $play_id;
        $this->audience = $audience;
    }
}