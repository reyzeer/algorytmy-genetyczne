<?php

namespace Functions;

class Func
{
    private int $bins = 22;
    private float $rangeStart = -1;
    private float $rangeEnd = 2;

    public function f(float $x): float
    {
        return $x * sin(10 * M_PI * $x) + 1;
    }

    public function fByBin(string $binary): float
    {
        $x = (bindec($binary) * ($this->rangeEnd - $this->rangeStart)) / (2**$this->bins) + $this->rangeStart;
        return $this->f($x);
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
