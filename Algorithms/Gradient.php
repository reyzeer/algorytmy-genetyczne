<?php

namespace Algorithms;

class Gradient extends Algorithm
{
//    private float $step = 0.01;
    private int $resolution = 10000;

    public function setUp(): void
    {
        // TODO: Implement setUp() method.
    }

    public function algorithm(): void
    {
        $x = $this->func->rangeStart;
        $step = ($this->func->rangeEnd - $this->func->rangeStart) / $this->resolution;

        $xtrace = [$x];
        $ftrace = [$this->func->f($x)];

        for ($i = 1; $i < $this->resolution; $i++) {
            $x = $x + $step;
            $xtrace[] = $x;
            $ftrace[] = $this->func->fDerivative($x);
        }

        $minValue = min($ftrace);
        $xOfMinValue = array_keys($xtrace, $minValue);

//        $x = 0.1;
//        $alpha = 0.6;
//        $j = 1000;
//
//        $xtrace = [$x];
//        $ftrace = [$this->func->f($x)];
//
//        for ($i = 1; $i <= $j; $i++) {
//            $x = $x - $alpha * $this->func->fDerivative($x);
//            $xtrace[] = $x;
//            $ftrace[] = $this->func->f($x);
//        }
    }
}
