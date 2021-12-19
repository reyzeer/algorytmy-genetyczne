<?php

namespace Algorithms;

class GreedyAlgorithm extends Algorithm
{
    /** @var float[] */
    private array $steps = [];
    private float $x;
    private string $xBinary;

    public function algorithm(): void
    {
        $currentStep = $this->func->randBin();
        $currentValue = $this->func->fByBin($currentStep);
        $this->steps[] = $currentValue;
        while (true) {
            $nextStep = $this->nextIter($currentStep);
            $nextValue = $this->func->fByBin($nextStep);
            $this->steps[] = $nextValue;

            if ($nextValue > $currentValue) {
                $this->x = $nextValue;
                $this->xBinary = $nextStep;
                break;
            }

            $currentStep = $nextStep;
            $currentValue = $nextValue;
        }
    }

    public function nextIter(string $currentIter): string
    {
        return decbin(bindec($currentIter) + 1);
    }

    public function result(): void
    {
        $i = 0;
        foreach ($this->steps as $step) {
            echo "Step $i. $step\n";
            $i++;
        }
        $f = $this->func->f($this->x);
        echo "Result: f($this->x) = $f\n";
    }

    public function getResultX(): float
    {
        return $this->x;
    }

    public function getResultXBinary(): string
    {
        return $this->xBinary;
    }

    public function getSteps(): array
    {
        return $this->steps;
    }
}
