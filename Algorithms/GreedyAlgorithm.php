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
        $this->checkDirection($representation);

        $currentFX = $this->func->fByBin($representation);
        $this->steps[] = $currentFX;
        while (true) {
            if ($this->toLeft) {
                $representation->prev();
            } else {
                $representation->next();
            }

            $this->steps[] = $representation->current();
            $nextFX = $this->func->fByBin($representation);
            if ($currentFX < $nextFX) {
                // trzeba dodać wybieranie kierunku w lewo < w prawo >
                $representation->prev();
                $this->x = $this->func->convertBinaryToX($representation);
                $this->fX = $currentFX;
                $this->xBinary = $representation;
                break;
            }
            $currentFX = $nextFX;
        }
    }

    private function checkDirection(BinaryOfFunc $representation): void
    {
        $current = $representation->current();
        $representation->next();
        $toLeft = $current > $representation->current();
        $representation->rewind();
    }

    private function step(): void
    {

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
