<?php

namespace Tests\Algorithms;

use Algorithms\AbstractAlgorithm;
use PHPUnit\Framework\TestCase;

abstract class AbstractAlgorithmTestCase extends TestCase
{
    abstract public function testAlgorithm();

    /**
     * @group multipleRunAlgorithm
     */
    public function testSomeRunning(): void
    {
        for ($i = 0; $i < 100; $i++) {
            $this->testAlgorithm();
        }
    }

    protected static function assertRange(AbstractAlgorithm $algorithm, float $x): void
    {
        self::assertGreaterThanOrEqual($algorithm->getFunc()->rangeStart, $x);
        self::assertLessThanOrEqual($algorithm->getFunc()->rangeEnd, $x);
    }
}
