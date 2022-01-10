<?php

namespace Algorithms\Func;

class GreedyAlgorithmBinary extends AbstractBinaryFuncFuncAlgorithm
{
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
                $this->saveResult();
                break;
            }
            $currentFX = $nextFX;
        }
    }
}
