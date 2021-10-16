<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;

use Theatrical\Play;

final class PlayTest extends TestCase
{
    public function testGetStringFormPlay(): void
    {
        $play = new Play('Hamlet', 'tragedy');

        self::assertSame('Hamlet : tragedy', $play->__toString());
    }
}
