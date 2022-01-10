<?php

namespace Tests\Algorithms\Func;

use Algorithms\Func\Annealing;
use Models\Func\Step;

class AnnealingTest extends AbstractFuncAlgorithmTestCase
{
    public function testAlgorithm(): void
    {
        $annealing = new Annealing();
        $annealing->setUp();
        $annealing->algorithm();
        $result = $annealing->getResult();

        self::assertRange($annealing, $result->x);
        self::assertGreaterThan(0, $annealing->getJumps());

        $steps = $annealing->getSteps();
        $minStep = new Step(PHP_FLOAT_MAX, PHP_FLOAT_MAX);
        foreach ($steps as $step) {
            if ($minStep->fX > $step->fX) {
                $minStep = $step;
            }
        }
        self::assertEquals($minStep->x, $result->x);
        self::assertEquals($minStep->fX, $result->fX);

        $algorithmStep = "1000000000";
        $xBinary = $result->representation;

        $xBinary->prev(bindec($algorithmStep));
        $fXLeft = $annealing->getFunc()->fByRepresentation($xBinary);

        $xBinary->next(2*bindec($algorithmStep));
        $fXRight = $annealing->getFunc()->fByRepresentation($xBinary);

        self::assertLessThanOrEqual($fXLeft, $result->fX);
        self::assertLessThanOrEqual($fXRight, $result->fX);
    }
}

