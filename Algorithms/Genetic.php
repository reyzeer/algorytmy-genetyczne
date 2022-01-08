<?php

namespace Algorithms;

use Exception;
use Representations\BinaryOfFunc;

class Genetic extends AbstractBinaryAlgorithm
{
    private $mutationPossibility = 0.1;

    private $numberOfSubjects = 10;
    /** @var string[] */
    private $subjects = [];
    private $subjectValues = [];

    public int $populationSize = 10;
    public float $survivalRate = 0.25;
    public int $iterations = 100000;

    /** @var BinaryOfFunc[] */
    private array $population = [];
    /** @var float[] */
    private array $memberFX = [];
    private float $sumOfMembersFX;
    /** @var float[] */
    private $memberGenerationAmount = [];

    /** @var BinaryOfFunc[] */
    private array $survivedMembers = [];
    /** @var BinaryOfFunc[] */
    private array $crossedMembers = [];

    public function algorithm(): void
    {
        $this->randomizeStart();
        for ($i = 0; $i < $this->iterations; $i++) {
            $this->calcMembersFX();
            $this->selectionSurvivedMembersClosestToTheMinimum();
//            $this->calcSumOfMembersFX();
//            $this->calcGenerationMemberAmount();
        }
    }

    public function randomizeStart(): void
    {
        for ($i = 0; $i < $this->populationSize; $i++) {
            $this->population[] = $this->func->randBin();
        }
    }

    public function calcMembersFX(): void
    {
        foreach ($this->population as $key => $member) {
            $this->memberFX[$key] = $this->func->fByRepresentation($member);
        }
    }

    public function calcSumOfMembersFX(): void
    {
        $this->sumOfMembersFX = array_sum($this->memberFX);
    }

    public function calcGenerationMemberAmount(): void
    {
        foreach ($this->population as $key => $member) {
            $memberFX = $this->func->fByRepresentation($member);
            $this->memberGenerationAmount[$key] = round($this->populationSize * $memberFX / $this->sumOfMembersFX);
            // tutaj czym wartośc mniejsza tym lepiej, trzeba przekazywać te osobniki dalej
        }
    }

    public function selectionSurvivedMembersClosestToTheMinimum(): void
    {
        rsort($this->population);
        $this->survivedMembers = array_slice($this->population, 0, ceil($this->populationSize * $this->survivalRate));
    }

    public function crossMembers(): void
    {
        $this->crossedMembers = [];
        $i = 0;
        $maxCrossedMembers = $this->populationSize - count($this->survivedMembers);
        foreach ($this->survivedMembers as $firstMember) {
            foreach ($this->survivedMembers as $secondMember) {
                if ($firstMember !== $secondMember) {
                    $crossedMember = (new BinaryOfFunc($this->func, $firstMember->current()));
                    $crossedMember->cross($secondMember);
                    $this->crossedMembers[] = $crossedMember;
                    $i++;
                    if ($i > $maxCrossedMembers) {
                        break 2;
                    }
                }
            }
        }
    }

    protected function prepareNextGeneration(): void
    {
        $this->population = array_merge(
            $this->survivedMembers,
            $this->crossedMembers
        );


        $survivedMembers = $this->selectionSurvivedMembersClosestToTheMinimum();
        $crossedMembers = [];
        $i = 0;
        $maxCrossedMembers = $this->populationSize - count($survivedMembers);
        foreach ($survivedMembers as $firstMember) {
            foreach ($survivedMembers as $secondMember) {
                if ($firstMember !== $secondMember) {
                    $crossedMember = (new BinaryOfFunc($this->func, $firstMember->current()));
                    $crossedMember->cross($secondMember);
                    $crossedMembers[] = $crossedMember;
                    $i++;
                    if ($i > $maxCrossedMembers) {
                        break 2;
                    }
                }
            }
        }


        $childs = [];
        foreach ($this->memberGenerationAmount as $key => $amount) {
            // wygenerowanie serii dzieci do połączenia w pary / kandydatów
        }
        // przekazanie dzieci do kolejnego pokolenia
        // łączenie do realizacji na klasie reprezentacji
    }

    // wykonanie mutacji
    // mutowanie do wykonania na klasie reprezentacji


    /**
     * @throws Exception
     */
//    public function run(): void
//    {
//        $this->randomizeStart();
//        for ($i = 0; $i < $this->steps; $i++) {
//            $this->calcMembersFX();
//            $this->calcAmountInNextGeneration();
//            $this->prepareNextGeneration();
//            $this->mutation();
//        }
//        $this->printResults();
//    }

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

    /**
     * @param BinaryOfFunc[] $population
     */
    public function setPopulation(array $population): void
    {
        $this->population = $population;
    }

    public function getPopulation(): array
    {
        return $this->population;
    }

    public function getMemberFX(): array
    {
        return $this->memberFX;
    }

    /**
     * @param BinaryOfFunc[]
     */
    public function setSurvivedMembers(array $survivedMembers): void
    {
        $this->survivedMembers = $survivedMembers;
    }

    public function getSurvivedMembers(): array
    {
        return $this->survivedMembers;
    }

    public function getCrossedMembers(): array
    {
        return $this->crossedMembers;
    }
}
