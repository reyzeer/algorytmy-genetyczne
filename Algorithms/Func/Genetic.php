<?php

namespace Algorithms\Func;

use Algorithms\GeneticAlgorithm;
use Algorithms\GeneticAlgorithmInterface;
use Representations\Func\Binary;

/**
 * @property Binary[] $population
 * @property Binary[] $survivedMembers
 * @property Binary[] $crossedMembers
 *
 * @method setPopulation(Binary[] $population)
 * @method setSurvivedMembers(Binary[] $survivedMembers)
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
     * @param Binary[] $population
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

    public function saveStep(): void
    {
        $this->representation = $this->population[0];
        parent::saveStep();
    }

    public function checkAlgorithmImproveResult(): void
    {
        $steps = count($this->steps);
        if ($steps > 2 && $this->steps[$steps-1]->fX >= $this->steps[$steps-2]->fX) {
            $this->iterationsWithoutImproveResult++;
        } else {
            $this->iterationsWithoutImproveResult = 0;
        }
    }
}
