<?php

namespace Algorithms;

use Models\Result;
use Models\Step;
use Representations\BinaryOfFunc;

abstract class AbstractBinaryAlgorithm extends AbstractAlgorithm
{
    protected BinaryOfFunc $representation;

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
