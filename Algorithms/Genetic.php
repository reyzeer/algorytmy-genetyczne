<?php

namespace Algorithms;

use Exception;
use Representations\BinaryOfFunc;

class Genetic extends AbstractAlgorithm
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


    private $steps = 100000;

    private int $populationSize = 10;
    private int $iterations = 100000;
    /** @var BinaryOfFunc[] */
    private array $population = [];
    /** @var float[] */
    private array $memberFX = [];
    private float $sumOfMembersFX;
    /** @var float[] */
    private $memberGenerationAmount = [];


    public function algorithm(): void
    {
        $this->randomizeStart();
        for ($i = 0; $i < $this->iterations; $i++) {
            $this->calcMembersFX();
            $this->calcSumOfMembersFX();
            $this->calcGenerationMemberAmount();
        }
    }

    protected function randomizeStart(): void
    {
        for ($i = 0; $i < $this->populationSize; $i++) {
            $this->population[] = $this->func->randBin();
        }
    }

    protected function calcMembersFX(): void
    {
        foreach ($this->population as $key => $member) {
            $this->memberFX[$key] = $this->func->fByRepresentation($member);
        }
    }

    protected function calcSumOfMembersFX(): void
    {
        $this->sumOfMembersFX = array_sum($this->memberFX);
    }

    protected function calcGenerationMemberAmount(): void
    {
        foreach ($this->population as $key => $member) {
            $memberFX = $this->func->fByRepresentation($member);
            $this->memberGenerationAmount[$key] = round($this->populationSize * $memberFX / $this->sumOfMembersFX);
            // tutaj czym wartośc mniejsza tym lepiej, trzeba przekazywać te osobniki dalej
        }
    }

    protected function prepareNextGeneration(): void
    {
        $childs = [];
        foreach ($this->memberGenerationAmount as $key => $amount) {
            // wygenerowanie serii dzieci do połączenia w pary / kandydatów
        }
        // przekazanie dzieci do kolejnego pokolenia
        // łączenie do realizacji na klasie reprezentacji
    }

    // wykonanie mutacji
    // mutowanie do wykonania na klasie reprezentacji

    public function result(): void
    {

    }

    /**
     * @throws Exception
     */
    public function run(): void
    {
        $this->randomizeStart();
        for ($i = 0; $i < $this->steps; $i++) {
            $this->calcMembersFX();
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

    /*public function prepareNextGeneration(): void
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
    }*/

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
        $this->calcMembersFX();
        foreach ($this->subjectValues as $key => $value) {
            echo "$key : {$this->subjects[$key]} => $value\n";
        }
        echo 'Max: ' . max($this->subjectValues);
    }
}
