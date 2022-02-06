<?php

namespace Algorithms;

abstract class AbstractAlgorithm
{
    protected float $time;

    public function run(): void
    {
        $this->setUp();
        $startTime = microtime(true);
        $this->algorithm();
        $this->time =  microtime(true) - $startTime;
    }

    abstract public function algorithm(): void;

    public function setUp(): void
    {
    }

    abstract protected function saveStep(): void;

    abstract protected function saveResult(): void;

    abstract public function result(): void;

    public function getTime(): float
    {
        return $this->time;
    }
}
