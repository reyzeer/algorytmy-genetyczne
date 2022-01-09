<?php

namespace Algorithms;

use Exception;
use Representations\BinaryOfFunc;
use RuntimeException;

class Genetic extends AbstractBinaryAlgorithm
{
    public int $populationSize = 20;
    public float $survivalRate = 0.25;
    public int $iterations = 2000;
    public float $mutationPossibility = 0.5;

    /** @var BinaryOfFunc[] */
    private array $population = [];
    /** @var BinaryOfFunc[] */
    private array $survivedMembers = [];
    /** @var BinaryOfFunc[] */
    private array $crossedMembers = [];

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        if ($this->survivalRate < 0 || $this->survivalRate > 0.5) {
            throw new RuntimeException('Survival rate mu be greater than 0 and lower than 0.5.');
        }

        if ($this->populationSize < (1/$this->survivalRate)**2) {
            throw new RuntimeException(
                'Population size must be equal or greater than inverse of square survival rate.'
            );
        }
    }

    /**
     * @throws Exception
     */
    public function algorithm(): void
    {
        $this->randomizeStart();
        for ($i = 0; $i < $this->iterations; $i++) {
            $this->selectionSurvivedMembersClosestToTheMinimum();
            $this->crossMembers();
            $this->prepareNextGeneration();
            $this->mutation();
            $this->saveStep();
        }
        $this->saveResult();
    }

    public function randomizeStart(): void
    {
        for ($i = 0; $i < $this->populationSize; $i++) {
            $this->population[] = $this->func->randBin();
        }
    }

    public function selectionSurvivedMembersClosestToTheMinimum(): void
    {
        $this->sortRepresentationsByF($this->population);
        $this->survivedMembers = array_slice($this->population, 0, ceil($this->populationSize * $this->survivalRate));
    }

    /**
     * @param BinaryOfFunc[]
     */
    public function sortRepresentationsByF(array &$population): void
    {
        usort($population, function (BinaryOfFunc $a, BinaryOfFunc $b): int {
            return $this->func->fByRepresentation($a) <=> $this->func->fByRepresentation($b);
        });
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
                    if ($i >= $maxCrossedMembers) {
                        break 2;
                    }
                }
            }
        }
    }

    public function prepareNextGeneration(): void
    {
        $this->population = array_merge($this->survivedMembers, $this->crossedMembers);
    }

    /**
     * @throws Exception
     */
    public function mutation(): void
    {
        foreach (array_slice($this->population, 1) as $member) {
            $member->mutation($this->mutationPossibility);
        }
    }

    public function saveStep(): void
    {
        $this->representation = $this->population[0];
        parent::saveStep();
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
