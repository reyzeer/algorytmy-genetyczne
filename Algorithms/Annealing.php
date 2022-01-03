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

    private float $iterations = 1000;
    private int $step;
    private float $alpha = 0.8;
    private float $temp = 200;
    private float $tempMin = 0.1;

    private array $steps = [];
    private BinaryOfFunc $representation;
    protected bool $toLeft;

    public function __construct()
    {
        parent::__construct();
        $this->step = bindec("1000000000");
    }

    public function algorithm(): void
    {
        $this->representation = $this->func->randBin();
        $this->representation->checkDirection();

        $maxTemp = -1000000;

        $min = PHP_FLOAT_MAX;
        $minRepresentation = "";

        while ($this->temp > $this->tempMin) {
            for ($i = 0; $i < $this->iterations; $i++) {

                $currentValue = $this->func->fByRepresentation($this->representation);
                $this->saveStep();
                if ($currentValue < $min) {
                    $min = $currentValue;
                    $minRepresentation = $this->representation->current();
                }

                // Get neighbour
                $this->representation->stepToMinima();
                $nextValue = $this->func->fByRepresentation($this->representation);
                $this->saveStep();

                $annealingPoint = exp(($nextValue - $currentValue) / $this->temp);
                if (!$annealingPoint > $this->rand0to1()) {
                    $this->representation->stepToMinima($this->step);
                }


            }

            $this->temp *= $this->alpha;
        }

    }

    private function saveStep(): void
    {
        $this->steps[] = [
            'x' => $this->func->convertRepresentationToX($this->representation),
            'fX' => $this->func->fByRepresentation($this->representation),
            'binary' => $this->representation->current(),
        ];
    }

    protected function rand0to1(): float
    {
        return mt_rand() / mt_getrandmax();
    }

    public function result(): void
    {
        $i = 0;
        foreach ($this->steps as $step) {
            echo 'Step ' . $i . '. f(' . $step['binary'] . ':' . $step['x'] . ') = ' . $step['fX'] . "\n";
            $i++;
        }
        $x = $this->func->convertRepresentationToX($this->representation);
        $result = $this->func->fByRepresentation($this->representation);
        echo "Result: f($x) = $result\n";
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
