<?php

namespace Tests\Algorithms;

use Algorithms\GradientDescent;

class GradientDescentTest extends AbstractAlgorithmTestCase
{
    public function testAlgorithm(): void
    {
        $gradient = new GradientDescent();
        $gradient->algorithm();
        $x = $gradient->getResultX();
        $steps = $gradient->getSteps();

        self::assertGreaterThanOrEqual($gradient->getFunc()->rangeStart, $x);
        self::assertLessThanOrEqual($gradient->getFunc()->rangeEnd, $x);
        self::assertLessThanOrEqual($gradient->maxIteration + 1, count($steps));

        // Going to minima
        $currentStep = $steps[0];
        foreach ($steps as $step) {
            self::assertTrue($currentStep >= $step['fX']);
            $currentStep = $step;
        }
    }
}
