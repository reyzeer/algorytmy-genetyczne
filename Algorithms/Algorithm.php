<?php

namespace Algorithms;

use Functions\Func;

abstract class Algorithm
{
    protected Func $func;

    public function __construct()
    {
        $this->func = new Func();
    }

    public function getFunc(): Func
    {
        return $this->func;
    }

    public function run(): void
    {
        $this->setUp();
        $startTime = microtime(true);
        $this->algorithm();
        $endTime =  microtime(true) - $startTime;
    }

    public function setUp(): void
    {
    }

    abstract public function algorithm(): void;

    abstract public function result(): void;
}
