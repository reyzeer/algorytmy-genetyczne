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

        $fX = $greedyAlgorithm->getResultFX();

        $xBinary = $greedyAlgorithm->getResultXBinary();

        $xBinary->prev();
        $fXLeft = $greedyAlgorithm->getFunc()->fByBin($xBinary);

        $xBinary->next();
        $xBinary->next();
        $fXRight = $greedyAlgorithm->getFunc()->fByBin($xBinary);

        self::assertLessThanOrEqual($fXLeft, $fX);
        self::assertLessThanOrEqual($fXRight, $fX);
    }
}
