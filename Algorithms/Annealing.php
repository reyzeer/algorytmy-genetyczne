<?php

namespace Algorithms;

use Functions\Func;
use Representations\BinaryOfFunc;

class Annealing extends Algorithm
{
    private int $elements = 10;
    /** @var string[] */
    private array $values = [];
    private array $representations = [];
    private ?Func $function = null;
    private array $gradients = [];
    private array $annealings = [];

    private float $iterations = 10;
    private float $step = 0.0001;
    private float $alpha = 0.8;
    private float $temp = 200;
    private float $tempMin = 0.1;

    private array $steps = [];
    private BinaryOfFunc $representation;
    protected bool $toLeft;

    public function algorithm(): void
    {
        $this->representation = $this->func->randBin();

        $maxTemp = -1000000;

        $min = PHP_FLOAT_MAX;
        $minRepresentation = "";

        while ($this->temp > $this->tempMin) {
            for ($i = 0; $i < $this->iterations; $i++) {

                $currentValue = $this->func->fByRepresentation($this->representation);
                $this->steps[] = $currentValue;
                if ($currentValue < $min) {
                    $min = $currentValue;
                    $minRepresentation = $this->representation->current();
                }

                // Get neighbour
                $this->representation->next();
                $nextValue = $this->func->fByRepresentation($this->representation);
                $this->steps[] = $nextValue;
                $annealingPoint = M_E ** (($currentValue - $nextValue) / $this->temp);
                if (!$annealingPoint > $this->rand0to1()) {
                    $this->representation->prev();
                }


            }

            $this->temp *= $this->alpha;
        }

    }

    protected function rand0to1(): float
    {
        return mt_rand() / mt_getrandmax();
    }

    protected function checkDirection(): void
    {

    }

    protected function step(): void
    {

    }

    protected function back(): void
    {

    }

    protected function jump(): void
    {

    }

    public function result(): void
    {
        $i = 0;
        foreach ($this->steps as $step) {
            echo "Step $i. $step\n";
            $i++;
        }
        $x = $this->func->convertRepresentationToX($this->representation);
        $result = $this->func->fByRepresentation($this->representation);
        echo "Result: f($x) = $result\n";
    }

    /*public function run(): void
    {
        $this->randomizeValues();
        $this->annealings();

//        $startTime = microtime(true);
//        $this->gradients();
//        $endTime =  microtime(true) - $startTime;


//        usort($this->gradients, static function ($a, $b) {
//            return $b[0] <=> $a[0]; });
        usort($this->annealings, static function ($a, $b) {
            return $b[0] <=> $a[0]; });

//        echo "{$this->gradients[0][1]} -> {$this->gradients[0][2]} = {$this->gradients[0][0]}, T=$endTime\n";

        foreach ($this->annealings as $annealing) {
            echo "{$annealing[1]} = {$annealing[0]}";
        }
    }*/

    /**
     * Randomize {$this->elements} with values between [-1, 2].
     */
    protected function randomizeValues(): void
    {
        for ($i = 0; $i < $this->elements; $i++) {
            $this->representations[] = $this->func->randBin();
        }
    }

    protected function annealings(): void
    {
        $this->annealings = [];
        foreach ($this->representations as $representation) {
            $this->annealings[] = $this->annealing($representation, 0.0001, 0.8, 200);
        }
    }

    protected function annealing(BinaryOfFunc $value, float $step, float $alpha, float $temp, float $minTemp = 0.1): array
    {
        $max = -1000000;
        while ($temp > $minTemp) {
            $p1 = $this->getFunc()->fByRepresentation($value);
            $p2 = ($this->function)($value + $step);
            if ($p1 > $p2) {
                return [$p1, $value];
            } else {
                $prob = $this->rand0to1();
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
}
