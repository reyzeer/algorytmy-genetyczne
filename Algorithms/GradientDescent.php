<?php

namespace Algorithms;

class GradientDescent extends Algorithm
{
    public int $maxIteration = 100;
    public float $stepRate = 0.0001;
    public float $resultTolerance = 0.0001;
    public bool $checkRange = false;

    private array $steps = [];
    private float $x;

    public function algorithm(): void
    {
        $start = $this->func->randX();
        $this->saveStep($start);
        $this->x = $start;

        for ($i = 0 ; $i < $this->maxIteration; $i++) {
            $diff = $this->stepRate * $this->func->fDerivative($this->x);
            if (abs($diff) < $this->resultTolerance) {
                break;
            }
            $this->x -= $diff;

            $this->controlOutOfRange();

            $this->saveStep($this->x);
        }
    }

    private function saveStep(float $x): void
    {
        $this->steps[] = [
            'x' => $x,
            'fX' => $this->func->f($x)
        ];
    }

    private function controlOutOfRange(): void
    {
        if ($this->checkRange) {
            if ($this->x < $this->func->rangeStart) {
                $this->x = $this->func->rangeStart;
            }

            if ($this->x > $this->func->rangeEnd) {
                $this->x = $this->func->rangeEnd;
            }
        }
    }

    public function result(): void
    {
        $i = 0;
        foreach ($this->steps as $step) {
            echo 'Step ' . $i . '. f(' . $step['x'] . ') = ' . $step['fX'] . "\n";
            $i++;
        }
        $f = $this->func->f($this->x);
        echo "Result: f($this->x) = $f\n";
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
