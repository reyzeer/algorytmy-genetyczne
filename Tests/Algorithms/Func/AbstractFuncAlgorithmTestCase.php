<?php

namespace Tests\Algorithms\Func;

use Algorithms\Func\AbstractFuncAlgorithm;
use Tests\Algorithms\AbstractAlgorithmTestCase;

abstract class AbstractFuncAlgorithmTestCase extends AbstractAlgorithmTestCase
{
    protected static function assertRange(AbstractFuncAlgorithm $algorithm, float $x): void
    {
        self::assertGreaterThanOrEqual($algorithm->getFunc()->rangeStart, $x);
        self::assertLessThanOrEqual($algorithm->getFunc()->rangeEnd, $x);
    }
}
