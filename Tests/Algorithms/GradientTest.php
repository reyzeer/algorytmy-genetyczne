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
        self::assertLessThanOrEqual($gradient->maxIteration + 1, count($steps));

        $fX = $gradient->getFunc()->f($x);
        $fXLeft = $gradient->getFunc()->f($x - 25 * $gradient->stepRate);
        $fXRight = $gradient->getFunc()->f($x + 25 * $gradient->stepRate);

        self::assertLessThanOrEqual($fXLeft, $fX);
        self::assertLessThanOrEqual($fXRight, $fX);
    }
}
