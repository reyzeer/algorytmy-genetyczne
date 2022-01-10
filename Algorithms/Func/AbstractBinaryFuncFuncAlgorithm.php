<?php

namespace Algorithms\Func;

use Models\Func\Result;
use Models\Func\Step;
use Representations\Func\Binary;

abstract class AbstractBinaryFuncFuncAlgorithm extends AbstractFuncAlgorithm
{
    protected Binary $representation;

    protected function saveStep(): void
    {
        $this->steps[] = new Step(
            $this->func->convertRepresentationToX($this->representation),
            $this->func->fByRepresentation($this->representation),
            $this->representation->current()
        );
    }

    protected function saveResult(): void
    {
        $this->result = new Result(
            $this->func->convertRepresentationToX($this->representation),
            $this->func->fByRepresentation($this->representation),
            $this->representation->current(),
            $this->func
        );
    }
}
