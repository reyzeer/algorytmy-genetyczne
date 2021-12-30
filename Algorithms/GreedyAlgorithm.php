<?php

namespace Algorithms;

use Representations\BinaryOfFunc;

class GreedyAlgorithm extends Algorithm
{
    /** @var float[] */
    private array $steps = [];
    private float $x;
    private float $fX;
    private BinaryOfFunc $xBinary;
    private BinaryOfFunc $representation;
    private bool $toLeft;

    public function algorithm(): void
    {
        $this->representation = $this->func->randBin();
        $this->checkDirection();

        $currentFX = $this->func->fByBin($this->representation);
        $this->steps[] = $currentFX;
        while (true) {
            $this->step();
            $this->steps[] = $this->representation->current();
            $nextFX = $this->func->fByBin($this->representation);
            if ($this->checkIsDone($currentFX, $nextFX)) {
                $this->backStep();
                $this->x = $this->func->convertBinaryToX($this->representation);
                $this->fX = $currentFX;
                $this->xBinary = $this->representation;
                break;
            }
            $currentFX = $nextFX;
        }
    }

    private function checkDirection(): void
    {
        $current = $this->representation->current();
        $this->representation->next();
        $this->toLeft = $current > $this->representation->current();
        $this->representation->rewind();
    }

    private function step(): void
    {
        if ($this->toLeft) {
            $this->representation->prev();
        } else {
            $this->representation->next();
        }
    }

    private function backStep(): void
    {
        if (!$this->toLeft) {
            $this->representation->prev();
        } else {
            $this->representation->next();
        }
    }

    private function checkIsDone(float $currentFX, float $nextFX): bool
    {
        if ($this->toLeft) {
            return $currentFX > $nextFX;
        } else {
            return $currentFX < $nextFX;
        }
    }

    public function result(): void
    {
        $i = 0;
        foreach ($this->steps as $step) {
            echo "Step $i. $step\n";
            $i++;
        }
        echo "Result: f($this->x) = $this->fX\n";
    }

    public function getResultX(): float
    {
        return $this->x;
    }

    public function getResultFX(): float
    {
        return $this->fX;
    }

    public function getResultXBinary(): BinaryOfFunc
    {
        return $this->xBinary;
    }
}
