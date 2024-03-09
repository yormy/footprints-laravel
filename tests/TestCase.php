<?php

namespace Yormy\FootprintsLaravel\Tests;

use Orchestra\Testbench\TestCase as Orchestra;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();
    }
}
