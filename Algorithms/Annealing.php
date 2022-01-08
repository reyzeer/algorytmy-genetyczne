<?php

namespace Algorithms;

use Models\Result;

class Annealing extends AbstractBinaryAlgorithm
{
    private float $iterations = 10000;
    private int $step;
    private float $alpha = 0.8;
    private float $temp = 10;
    private float $tempMin = 1;

    private int $jumps = 0;
    private int $cold = 0;

    public function setUp(): void
    {
        $this->step = bindec("1000000000");
    }

    public function algorithm(): void
    {
        $this->representation = $this->func->randBin();
        $this->representation->checkDirection();
        $this->result = new Result(
            PHP_FLOAT_MAX,
            PHP_FLOAT_MAX
        );

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
        if ($currentValue < $this->result->fX) {
            $this->saveResult();
        }
    }

    protected function rand0to1(): float
    {
        return mt_rand() / mt_getrandmax();
    }

    public function result(): void
    {
        parent::result();
        echo "Jumps: $this->jumps\n";
        echo "Colds: $this->cold\n";
    }

    public function getJumps(): int
    {
        return $this->jumps;
    }
}
