<?php

namespace Algorithms;

class Gradient extends Algorithm
{
    public int $maxIteration = 5;
    public float $stepRate = 0.1;
    public float $resultTolerance = 0.01;

    /** @var float[] */
    private array $steps = [];
    private float $x;

    public function algorithm(): void
    {
        $start = $this->func->randX();
        $this->steps = [$start];
        $this->x = $start;

        for ($i = 0 ; $i < $this->maxIteration; $i++) {
            $diff = $this->stepRate * $this->func->fDerivative($this->x);
            if (abs($diff) < $this->resultTolerance) {
                break;
            }
            $this->x -= $diff;
            $this->steps[] = $this->x;
        }
    }

    public function result(): void
    {
        echo "Result: $this->x \n";
        $i = 0;
        foreach ($this->steps as $step) {
            echo "Step $i. $step\n";
            $i++;
        }
    }

    public function getResultX(): float
    {
        return $this->x;
    }

    public function getSteps(): array
    {
        return $this->steps;
    }
}
