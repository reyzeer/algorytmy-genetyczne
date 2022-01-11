<?php

namespace Representations\Func;

interface FuncRepresentationInterface
{
    public function checkDirection(): void;
    public function toLeft(): bool;
    public function stepToMinima(int $step = 1): void;
    public function backStepToMinima(int $step = 1): void;
    public function fValue(): float;
}
