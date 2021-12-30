<?php

namespace Functions;

use Representations\BinaryOfFunc;

class Func
{
    public int $bits = 22;
    public float $rangeStart = -1;
    public float $rangeEnd = 2;

    public function f(float $x): float
    {
        return $x * sin(10 * M_PI * $x) + 1;
    }

    public function fDerivative(float $x): float
    {
        return sin(10 * M_PI * $x) + 10 * M_PI * $x * cos(10 * M_PI * $x);
    }

    public function fByBin(BinaryOfFunc $representation): float
    {
        return $this->f($this->convertBinaryToX($representation));
    }

    public function convertBinaryToX(BinaryOfFunc $representation): float
    {
        $binary = $representation->current();
        return ($this->rangeEnd - $this->rangeStart) * (bindec($binary) / (2**$this->bits - 1)) + $this->rangeStart;
    }

    public function randX(): float
    {
        return $this->convertBinaryToX($this->randBin());
    }

    public function randBin(): BinaryOfFunc
    {
        $representation = new BinaryOfFunc($this->bits);
        $representation->generateRand();
        return $representation;
    }
}
