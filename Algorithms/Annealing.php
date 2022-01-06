<?php

namespace Algorithms;

use Representations\BinaryOfFunc;

class Annealing extends Algorithm
{
    private float $iterations = 10000;
    private int $step;
    private float $alpha = 0.8;
    private float $temp = 10;
    private float $tempMin = 1;

    private BinaryOfFunc $representation;
    private array $steps = [];
    private float $minX;
    private float $min;
    private string $minRepresentation;
    private int $jumps = 0;
    private int $cold = 0;

    public function __construct()
    {
        parent::__construct();
        $this->step = bindec("1000000000");
    }

    public function algorithm(): void
    {
        $this->representation = $this->func->randBin();
        $this->representation->checkDirection();

        $this->min = PHP_FLOAT_MAX;
        $this->minRepresentation = "";

        while ($this->temp > $this->tempMin) {
            for ($i = 0; $i < $this->iterations; $i++) {

                $currentValue = $this->func->fByRepresentation($this->representation);
                $this->saveStep();
                $this->saveMin($currentValue);

                $this->representation->stepToMinima($this->step);
                $nextValue = $this->func->fByRepresentation($this->representation);
                $this->saveStep();
                $this->saveMin($nextValue);

                $annealingPoint = exp(($nextValue - $currentValue) / $this->temp);
                if ($annealingPoint < $this->rand0to1()) {
                    $this->representation->backStepToMinima($this->step);
                    $this->representation->stepToMinima();
                    $this->cold++;
                } else  {
                    $this->jumps++;
                }
            }

            $this->temp *= $this->alpha;
        }

    }

    private function saveMin(float $currentValue): void
    {
        if ($currentValue < $this->min) {
            $this->minX = $this->func->convertRepresentationToX($this->representation);
            $this->min = $currentValue;
            $this->minRepresentation = $this->representation->current();
        }
    }

    private function saveStep(): void
    {
        $this->steps[] = [
            'x' => $this->func->convertRepresentationToX($this->representation),
            'fX' => $this->func->fByRepresentation($this->representation),
            'binary' => $this->representation->current(),
        ];
    }

    protected function rand0to1(): float
    {
        return mt_rand() / mt_getrandmax();
    }

    public function result(): void
    {
        $i = 0;
        foreach ($this->steps as $step) {
            echo 'Step ' . $i . '. f(' . $step['binary'] . ':' . $step['x'] . ') = ' . $step['fX'] . "\n";
            $i++;
        }
        echo "Result: f($this->minX) = $this->min\n";
        echo "Jumps: $this->jumps\n";
        echo "Colds: $this->cold\n";
    }

    public function getResultX(): float
    {
        return $this->minX;
    }

    public function getResultFX(): float
    {
        return $this->min;
    }

    public function getMinRepresentation(): BinaryOfFunc
    {
        return new BinaryOfFunc($this->func, $this->minRepresentation);
    }

    public function getSteps(): array
    {
        return $this->steps;
    }

    public function getJumps(): int
    {
        return $this->jumps;
    }
}
