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

        $xBinary = $greedyAlgorithm->getResultXBinary();
        $fX = $greedyAlgorithm->getFunc()->fByBin($xBinary);
        $fXLeft = $greedyAlgorithm->getFunc()->f(decbin(bindec($xBinary) - 1));
        $fXRight = $greedyAlgorithm->getFunc()->f($greedyAlgorithm->nextIter($xBinary));

        self::assertLessThanOrEqual($fXLeft, $fX);
        self::assertLessThanOrEqual($fXRight, $fX);
    }
}
