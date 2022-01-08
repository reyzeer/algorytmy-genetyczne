<?php

namespace Tests\Algorithms;

use Algorithms\GreedyAlgorithm;

class GreedyAlgorithmTest extends AbstractAlgorithmTestCase
{
    public function testAlgorithm(): void
    {
        $greedyAlgorithm = new GreedyAlgorithm();
        $greedyAlgorithm->algorithm();
        $result = $greedyAlgorithm->getResult();

        self::assertRange($greedyAlgorithm, $result->x);

        $xBinary = $result->representation;

        $xBinary->prev();
        $fXLeft = $greedyAlgorithm->getFunc()->fByRepresentation($xBinary);

        $xBinary->next();
        $xBinary->next();
        $fXRight = $greedyAlgorithm->getFunc()->fByRepresentation($xBinary);

        self::assertLessThanOrEqual($fXLeft, $result->fX);
        self::assertLessThanOrEqual($fXRight, $result->fX);
    }
}
