<?php

namespace Tests\Algorithms;

use PHPUnit\Framework\TestCase;

abstract class AbstractAlgorithmTestCase extends TestCase
{
    abstract public function testAlgorithm();

    public function testSomeRunning(): void
    {
//        $this->markTestSkipped('Use this test, after made changes in GreedyAlgorithm.');
        for ($i = 0; $i < 100; $i++) {
            $this->testAlgorithm();
        }
    }
}