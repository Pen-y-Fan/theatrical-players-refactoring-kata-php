<?php
declare(strict_types=1);

namespace Theatrical;

class Play 
{
    public $name;
    public $type;

    public function __construct($name, $type)
    {
        $this->name = $name;
        $this->type = $type;
    }

    public function __toString()
    {
        return (string) $this->name . ' : ' . $this->type;
    }
}