<?php

namespace Tests\Algorithms;

use Algorithms\Func\AbstractFuncAlgorithm;
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
}
