<?php

namespace Functions;

use Representations\Func\Binary;

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

    public function fByRepresentation(Binary $representation): float
    {
        return $this->f($this->convertRepresentationToX($representation));
    }

    public function convertRepresentationToX(Binary $representation): float
    {
        $binary = $representation->current();
        return ($this->rangeEnd - $this->rangeStart) * (bindec($binary) / (2**$this->bits - 1)) + $this->rangeStart;
    }

    public function randX(): float
    {
        return $this->convertRepresentationToX($this->randBin());
    }

    public function randBin(): Binary
    {
        $representation = new Binary($this);
        $representation->generateRand();
        return $representation;
    }
}
