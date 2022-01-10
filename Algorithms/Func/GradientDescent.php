<?php

namespace Algorithms\Func;

use Models\Func\Result;
use Models\Func\Step;

class GradientDescent extends AbstractFuncAlgorithm
{
    public int $maxIteration = 100;
    public float $stepRate = 0.0001;
    public float $resultTolerance = 0.0001;
    public bool $checkRange = false;

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

        $this->saveResult();
    }

    protected function saveStep(float $x = PHP_FLOAT_MAX): void
    {
        $this->steps[] = new Step($x, $this->func->f($x));
    }

    protected function saveResult(): void
    {
        $this->result = new Result(
            $this->x,
            $this->func->f($this->x)
        );
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
}
