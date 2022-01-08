<?php

namespace Algorithms;

use Functions\Func;
use Models\Result;
use Models\Step;

abstract class AbstractAlgorithm
{
    protected Func $func;
    /** @var Step[] */
    protected array $steps;
    protected Result $result;
    protected float $time;

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
        $this->time =  microtime(true) - $startTime;
    }

    public function setUp(): void
    {
    }

    abstract public function algorithm(): void;

    abstract protected function saveStep(): void;

    abstract protected function saveResult(): void;

    /**
     * @return Step[]
     */
    public function getSteps(): array
    {
        return $this->steps;
    }

    public function getResult(): Result
    {
        return $this->result;
    }

    public function result(): void
    {
        $binary = !empty($this->steps[0]->binary);

        $i = 0;
        foreach ($this->steps as $step) {
            $fString =
                'f(' .
                    (($binary) ? $step->binary . ' : ' : '') .
                    $step->x .
                ')';
            echo "Step $i. $fString = $step->fX \n";
            $i++;
        }

        $fString =
            'f(' .
                (($binary) ? $this->result->binary . ' : ' : '') .
                $this->result->x .
            ')';
        echo "Result: $fString = " . $this->result->fX . "\n";
    }

    public function getTime(): float
    {
        return $this->time;
    }
}
