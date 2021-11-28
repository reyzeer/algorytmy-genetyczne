<?php

namespace Algorithms;

use Functions\Func;

abstract class Algorithm
{
    protected Func $func;

    public function __construct()
    {
        $this->func = new Func;
    }

    public function run(): void
    {
        $this->setUp();
        $startTime = microtime(true);
        $this->algorithm();
        $endTime =  microtime(true) - $startTime;
    }

    abstract public function setUp(): void;

    abstract public function algorithm(): void;
}
