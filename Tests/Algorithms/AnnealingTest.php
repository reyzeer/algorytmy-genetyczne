<?php

namespace Tests\Algorithms;

use Algorithms\Annealing;

class AnnealingTest extends AbstractAlgorithmTestCase
{
    public function testAlgorithm(): void
    {
        $annealing = new Annealing();
        $annealing->algorithm();
        $x = $annealing->getResultX();
        $fX = $annealing->getResultFX();

        self::assertGreaterThanOrEqual($annealing->getFunc()->rangeStart, $x);
        self::assertLessThanOrEqual($annealing->getFunc()->rangeEnd, $x);

        self::assertGreaterThan(0, $annealing->getJumps());

        $steps = $annealing->getSteps();
        $minX = PHP_FLOAT_MAX;
        $minFX = PHP_FLOAT_MAX;
        foreach ($steps as $step) {
            if ($minFX > $step['fX']) {
                $minX = $step['x'];
                $minFX = $step['fX'];
            }
        }
        self::assertEquals($minX, $x);
        self::assertEquals($minFX, $fX);

        $xBinary = $annealing->getMinRepresentation();

        $xBinary->prev(bindec("1000000000"));
        $fXLeft = $annealing->getFunc()->fByRepresentation($xBinary);

        $xBinary->next(2*bindec("1000000000"));
        $fXRight = $annealing->getFunc()->fByRepresentation($xBinary);

        self::assertLessThanOrEqual($fXLeft, $fX);
        self::assertLessThanOrEqual($fXRight, $fX);
    }
}

