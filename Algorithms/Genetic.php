<?php

namespace Algorithms;

use Exception;

class Genetic
{
    private $function = null;
    private $startRange = -1;
    private $endRange = 2;
    private $bits = 22;
    private $mutationPossibility = 0.1;

    private $numberOfSubjects = 10;
    /** @var string[] */
    private $subjects = [];
    private $subjectValues = [];
    private $amountInNextGeneration = [];

    private $steps = 100000;

    public function __construct()
    {
        $this->function = static function($x) {
            return $x * sin(10 * M_PI * $x) + 1;
        };
    }

    /**
     * @throws Exception
     */
    public function run(): void
    {
        $this->randomizeStart();
        for ($i = 0; $i < $this->steps; $i++) {
            $this->calcValues();
            $this->calcAmountInNextGeneration();
            $this->prepareNextGeneration();
            $this->mutation();
        }
        $this->printResults();
    }

    protected function getValue(string $binary): float
    {
        return ($this->function)(abs
            ($this->startRange - $this->endRange) * bindec($binary)/(2**$this->bits - 1) - 1);
    }

    /**
     * @throws Exception
     */
    protected function randomizeStart(): void
    {
        for ($i = 0; $i < $this->numberOfSubjects; $i++) {
            $subject = '';
            for ($j = 0 ; $j < $this->bits; $j++) {
                $subject .= random_int(0, 1) === 0 ? '0' : '1';
            }
            $this->subjects[] = $subject;
        }
    }

    protected function calcValues(): void
    {
        foreach ($this->subjects as $key => $subject) {
            $this->subjectValues[$key] = bindec($subject);
        }
    }

    protected function calcAmountInNextGeneration(): void
    {
        foreach ($this->subjectValues as $key => $value)  {
            $this->subjectValues[$key] = ($this->function)($value);
        }
        $sumOfValues = array_sum($this->subjectValues);
        foreach ($this->subjectValues as $key => $value) {
            $this->amountInNextGeneration[$key] = round($this->numberOfSubjects * $value / $sumOfValues);
        }
    }

    public function prepareNextGeneration(): void
    {
        $nextGeneration = [];
        foreach ($this->amountInNextGeneration as $key => $amount) {
            for ($i = 0; $i < $amount; $i++) {
                $nextGeneration[] = $this->subjects[$key];
            }
        }
        for ($i = 0; $i < $this->numberOfSubjects; $i += 2) {
            $this->subjects[$i] =
                substr($nextGeneration[$i], 0, $this->bits / 2)
                . substr($nextGeneration[$i + 1], $this->bits / 2, $this->bits / 2);
        }
    }

    /**
     * @throws Exception
     */
    public function mutation(): void
    {
        foreach ($this->subjects as $key => $subject) {
            if (random_int(0, 1 / $this->mutationPossibility) === 1) {
                $bitIndex = random_int(0, $this->bits - 1);
                $this->subjects[$key][$bitIndex] = $this->subjects[$key][$bitIndex] === '0' ? '1' : '0';
            }
        }
    }

    protected function printResults(): void
    {
        $this->calcValues();
        foreach ($this->subjectValues as $key => $value) {
            echo "$key : {$this->subjects[$key]} => $value\n";
        }
        echo 'Max: ' . max($this->subjectValues);
    }
}
