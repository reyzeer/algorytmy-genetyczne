<?php

namespace Tests\Algorithms;

use Algorithms\GreedyAlgorithm;

class GreedyAlgorithmTest extends AbstractAlgorithmTestCase
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
        $fXLeft = $greedyAlgorithm->getFunc()->fByRepresentation($xBinary);

        $xBinary->next();
        $xBinary->next();
        $fXRight = $greedyAlgorithm->getFunc()->fByRepresentation($xBinary);

        self::assertLessThanOrEqual($fXLeft, $fX);
        self::assertLessThanOrEqual($fXRight, $fX);
    }
}
