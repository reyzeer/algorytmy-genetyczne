<?php

namespace Tests\Algorithms;

use Algorithms\Gradient;
use PHPUnit\Framework\TestCase;

class GradientTest extends TestCase
{
    public function testAlgorithm(): void
    {
        $gradient = new Gradient();
        $gradient->algorithm();
        $x = $gradient->getResultX();
        $steps = $gradient->getSteps();
        self::assertGreaterThanOrEqual($gradient->getFunc()->rangeStart, $x);
        self::assertLessThanOrEqual($gradient->getFunc()->rangeEnd, $x);
        self::assertCount($gradient->maxIteration + 1, $steps);
    }
}
