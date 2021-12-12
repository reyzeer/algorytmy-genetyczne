<?php

namespace Functions;

class Func
{
    public int $bins = 22;
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

    public function fByBin(string $binary): float
    {
        return $this->f($this->convertBinaryToX($binary));
    }

    public function convertBinaryToX(string $binary): float
    {
        return ($this->rangeEnd - $this->rangeStart) * (bindec($binary) / (2**$this->bins - 1)) + $this->rangeStart;
    }

    public function randX(): float
    {
        return $this->convertBinaryToX($this->randBin());
    }

    public function randBin(): string
    {
        $result = "";
        for ($i = 0; $i < $this->bins; $i++) {
            $result .= mt_rand() % 2 ? "1" : "0";
        }
        return $result;
    }
}
