<?php

namespace Algorithms;

use Representations\BinaryOfFunc;

class GreedyAlgorithm extends Algorithm
{
    private array $steps = [];

    private float $x;
    private float $fX;
    private BinaryOfFunc $xBinary;
    private BinaryOfFunc $representation;

    public function algorithm(): void
    {
        $this->representation = $this->func->randBin();
        $this->representation->checkDirection();

        $currentFX = $this->func->fByRepresentation($this->representation);
        $this->saveStep();
        while (true) {
            $this->representation->stepToMinima();
            $this->saveStep();
            $nextFX = $this->func->fByRepresentation($this->representation);
            if ($nextFX > $currentFX) {
                $this->representation->backStepToMinima();
                $this->x = $this->func->convertRepresentationToX($this->representation);
                $this->fX = $currentFX;
                $this->xBinary = $this->representation;
                break;
            }
            $currentFX = $nextFX;
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

    public function result(): void
    {
        $i = 0;
        foreach ($this->steps as $step) {
            echo 'Step ' . $i . '. f(' . $step['binary'] . ':' . $step['x'] . ') = ' . $step['fX'] . "\n";
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
