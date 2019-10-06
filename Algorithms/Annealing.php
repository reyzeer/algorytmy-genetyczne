<?php

namespace Algorithms;

class Annealing
{

    private $elements = 10;
    private $values = [];
    private $function = null;
    private $gradients = [];

    public function __construct()
    {
        $this->function = static function($x) {
            return $x * sin(10 * M_PI * $x) + 1;
        };
    }

    public function run(): void
    {
        $this->randomizeValues();
        $this->gradients();
        $annealing = [];
        foreach ($this->values as $value) {
            $annealing[] = $this->annealing($value, 0.0001, 0.8, 200);
        }
        usort($this->gradients, static function ($a, $b) {
            return $b[0] <=> $a[0]; });
        usort($annealing, static function ($a, $b) {
            return $b[0] <=> $a[0]; });

        echo "{$this->gradients[0][1]} = {$this->gradients[0][0]}, T=\n";
        echo "{$annealing[0][1]} = {$annealing[0][0]}, T=\n";
    }

    /**
     * Randomize {$this->elements} with values between [-1, 2].
     */
    protected function randomizeValues(): void
    {
        for ($i = 0; $i < $this->elements; $i++) {
            $this->values[] = 3 * $this->random0to1() - 1;
        }
    }

    protected function gradients(): void
    {
        foreach ($this->values as $value) {
            $this->gradients[] = $this->gradient($value,0.001, 100);
        }
    }

    protected function gradient($value, $step, $maxIter): array
    {
        for ($iter = 0, $max = -1000000; $iter < $maxIter; $iter++) {
            $p1 = ($this->function)($value);
            $p2 = ($this->function)($value + $step);
            if ($p1 > $p2) {
                return [$p1, $value];
            }
            $max = $p2;
        }
        return [$max, $value + $step];
    }

    protected function annealing($value, $step, $alpha, $temp, $minTemp = 0.1): array
    {
        $max = -1000000;
        while ($temp > $minTemp) {
            $p1 = ($this->function)($value);
            $p2 = ($this->function)($value + $step);
            if ($p1 > $p2) {
                return [$p1, $value];
            } else {
                $prob = $this->random0to1();
                if ($prob < exp($p1 - $p2 / $temp)) {
                    $value += $step;
                    $max = $p2;
                    if ($value > 2) {
                        return [$max, $value];
                    }
                }
            }

            $max = $p2;
            $temp *= $alpha;
        }
        return [$max, $value + $step];
    }

    protected function random0to1(): float
    {
        return mt_rand() / mt_getrandmax();
    }
}
