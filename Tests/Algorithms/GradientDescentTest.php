<?php

namespace Tests\Algorithms;

use Algorithms\GradientDescent;

class GradientDescentTest extends AbstractAlgorithmTestCase
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
