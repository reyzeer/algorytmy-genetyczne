<?php

namespace Algorithms;

use Functions\Func;

class Annealing
{
    private int $elements = 10;
    /** @var string[] */
    private array $values = [];
    private ?Func $function = null;
    private $gradients = [];
    private $annealings = [];

    public function __construct()
    {
        $this->function = new Func();
    }

    public function run(): void
    {
        $this->randomizeValues();
        $startTime = microtime(true);
        $this->gradients();
        $endTime =  microtime(true) - $startTime;

//        $startTime = microtime(true);
//        $this->annealings();
//        $endTime2 = microtime(true) - $startTime;

        usort($this->gradients, static function ($a, $b) {
            return $b[0] <=> $a[0]; });
//        usort($this->annealings, static function ($a, $b) {
//            return $b[0] <=> $a[0]; });

        echo "{$this->gradients[0][1]} -> {$this->gradients[0][2]} = {$this->gradients[0][0]}, T=$endTime\n";
//        echo "{$this->annealings[0][1]} = {$this->annealings[0][0]}, T=$endTime2\n";
    }

    /**
     * Randomize {$this->elements} with values between [-1, 2].
     */
    protected function randomizeValues(): void
    {
        for ($i = 0; $i < $this->elements; $i++) {
            $this->values[] = $this->function->randBin();
        }
    }

    protected function gradients(): void
    {
        foreach ($this->values as $value) {
            $this->gradients[] = $this->gradient($value, 100);
        }
    }

    protected function gradient($value, $maxIter): array
    {
        for ($iter = 0, $min = 1000000; $iter < $maxIter; $iter++) {
            $p1 = $this->function->fByBin($value);
            $valueP2 = $this->nextIter($value);
            $p2 = $this->function->fByBin($valueP2);
            if ($p1 < $p2) {
                return [$p1, $value];
            }
            $min = $p2;
            $value = $this->nextIter($value);
        }
        return [$min, $valueP2, bindec($valueP2)];
    }

    protected function nextIter(string $currentIter): string
    {
        return decbin(bindec($currentIter) + 1);
    }

    protected function annealings(): void
    {
        $this->annealings = [];
        foreach ($this->values as $value) {
            $this->annealings[] = $this->annealing($value, 0.0001, 0.8, 200);
        }
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
