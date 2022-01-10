<?php

namespace Tests\Algorithms\Func;

use Algorithms\Func\GreedyAlgorithmBinary;

class GreedyAlgorithmTest extends AbstractFuncAlgorithmTestCase
{
    public function testAlgorithm(): void
    {
        $greedyAlgorithm = new GreedyAlgorithmBinary();
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
