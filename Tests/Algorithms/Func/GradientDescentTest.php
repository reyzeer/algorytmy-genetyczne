<?php

namespace Tests\Algorithms\Func;

use Algorithms\Func\GradientDescent;

class GradientDescentTest extends AbstractFuncAlgorithmTestCase
{
    public function testAlgorithm(): void
    {
        $gradient = new GradientDescent();
        $gradient->algorithm();
        $x = $gradient->getResult()->x;
        $steps = $gradient->getSteps();

        self::assertRange($gradient, $x);
        self::assertLessThanOrEqual($gradient->maxIteration + 1, count($steps));

        // Going to minima
        $currentMinima = $steps[0];
        foreach ($steps as $step) {
            self::assertTrue($currentMinima->fX >= $step->fX);
            $currentMinima = $step;
        }
    }
}
