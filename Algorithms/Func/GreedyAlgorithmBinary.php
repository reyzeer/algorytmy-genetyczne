<?php

namespace Algorithms\Func;

class GreedyAlgorithmBinary extends AbstractBinaryFuncFuncAlgorithm
{
    public function algorithm(): void
    {
        $this->representation = $this->func->randBin();
        $this->representation->checkDirection();

        $currentFX = $this->representation->fValue();
        $this->saveStep();
        while (true) {
            $this->representation->stepToMinima();
            $this->saveStep();
            $nextFX = $this->representation->fValue();
            if ($nextFX > $currentFX) {
                $this->representation->backStepToMinima();
                $this->saveResult();
                break;
            }
            $currentFX = $nextFX;
        }
    }
}
