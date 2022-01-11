<?php

namespace Algorithms\Func;

use Algorithms\GeneticAlgorithm;
use Algorithms\GeneticAlgorithmInterface;
use Exception;
use Representations\Func\Binary;

/**
 * @property Binary[] $population
 * @property Binary[] $survivedMembers
 * @property Binary[] $crossedMembers
 */
class Genetic extends AbstractBinaryFuncFuncAlgorithm implements GeneticAlgorithmInterface
{
    use GeneticAlgorithm;

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
     * @param Binary[]
     */
    public function sortRepresentationsByF(array &$population): void
    {
        usort($population, static function (Binary $a, Binary $b): int {
            return $a->fValue() <=> $b->fValue();
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
                    $crossedMember = (new Binary($this->func, $firstMember->current()));
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
}
