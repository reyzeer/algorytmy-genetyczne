<?php

namespace Tests\Algorithms;

use Algorithms\GradientDescent;
use PHPUnit\Framework\TestCase;

class GradientDescentTest extends TestCase
{
    public function testAlgorithm(): void
    {
        $gradient = new GradientDescent();
        $gradient->algorithm();
        $x = $gradient->getResultX();
        $steps = $gradient->getSteps();

        self::assertGreaterThanOrEqual($gradient->getFunc()->rangeStart, $x);
        self::assertLessThanOrEqual($gradient->getFunc()->rangeEnd, $x);
        self::assertLessThanOrEqual($gradient->maxIteration + 1, count($steps));

        $fX = $gradient->getFunc()->f($x);
        $fXLeft = $gradient->getFunc()->f($x -  $gradient->stepRate);
        $fXRight = $gradient->getFunc()->f($x + $gradient->stepRate);

        self::assertLessThanOrEqual($fXLeft, $fX);
        self::assertLessThanOrEqual($fXRight, $fX);
    }
}
