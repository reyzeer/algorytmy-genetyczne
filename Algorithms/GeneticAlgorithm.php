<?php

namespace Algorithms;

use Exception;
use RuntimeException;

trait GeneticAlgorithm
{
    public int $populationSize = 20;
    public float $survivalRate = 0.25;
    public int $iterations = 2000;
    public int $minIterations = 1000;
    public int $iterationsWithoutImproveResultBeforeStop = 500;
    public float $mutationPossibility = 0.5;

    private array $population = [];
    private array $survivedMembers = [];
    private array $crossedMembers = [];
    private int $i = 0;
    private int $iterationsWithoutImproveResult = 0;

    /**
     * @throws RuntimeException
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
        for ($this->i = 0; $this->i < $this->iterations; $this->i++) {
            $this->selectionSurvivedMembersClosestToTheMinimum();
            $this->crossMembers();
            $this->prepareNextGeneration();
            $this->mutation();
            $this->saveStep();
            $this->checkAlgorithmImproveResult();
            if ($this->checkAlgorithmStoppedImproveResult()) {
                break;
            }
        }
        $this->saveResult();
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

    abstract public function checkAlgorithmImproveResult(): void;

    public function checkAlgorithmStoppedImproveResult(): bool
    {
        if ($this->i <= $this->minIterations) {
            return false;
        }
        if ($this->iterationsWithoutImproveResult >= $this->iterationsWithoutImproveResultBeforeStop) {
            return true;
        }
        return false;
    }

    public function setPopulation(array $population): void
    {
        $this->population = $population;
    }

    public function getPopulation(): array
    {
        return $this->population;
    }

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

    public function getIterationsWithoutImproveResult(): int
    {
        return $this->iterationsWithoutImproveResult;
    }
}
