<?php

namespace Algorithms;

class GreedyAlgorithm extends Algorithm
{
    /** @var float[] */
    private array $steps = [];
    private float $x;

    public function algorithm(): void
    {
        $currentStep = $this->func->randX();
        $currentValue = $this->func->fByBin($currentStep);
        $this->steps[] = $currentValue;
        while (true) {
            $nextStep = $this->nextIter($currentStep);
            $nextValue = $this->func->fByBin($nextStep);
            $this->steps[] = $nextValue;

            if ($nextValue > $currentValue) {
                $this->x = $nextValue;
                break;
            }

            $currentStep = $nextStep;
            $currentValue = $nextValue;
        }
    }

    protected function nextIter(string $currentIter): string
    {
        return decbin(bindec($currentIter) + 1);
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
}
