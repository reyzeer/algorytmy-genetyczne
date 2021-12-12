<?php

namespace Tests\Algorithms;

use Algorithms\GreedyAlgorithm;
use PHPUnit\Framework\TestCase;

class GreedyAlgorithmTest extends TestCase
{
    public function testAlgorithm(): void
    {
        $greedyAlgorithm = new GreedyAlgorithm();
        $greedyAlgorithm->algorithm();
        $x = $greedyAlgorithm->getResultX();

        self::assertGreaterThanOrEqual($greedyAlgorithm->getFunc()->rangeStart, $x);
        self::assertLessThanOrEqual($greedyAlgorithm->getFunc()->rangeEnd, $x);

        $fX = $greedyAlgorithm->getFunc()->f($x);
        $fXLeft = $greedyAlgorithm->getFunc()->f($x - $greedyAlgorithm->stepRate);
        $fXRight = $greedyAlgorithm->getFunc()->f($x + $greedyAlgorithm->stepRate);

        self::assertLessThanOrEqual($fXLeft, $fX);
        self::assertLessThanOrEqual($fXRight, $fX);
    }
}
