<?php

namespace Algorithms\TSP;

use Algorithms\AbstractAlgorithm;
use Algorithms\GeneticAlgorithm;
use Algorithms\GeneticAlgorithmInterface;
use Exception;
use Representations\TSP\Graph;
use Representations\TSP\Route;
use RuntimeException;

/**
 * @property Route[] $population
 * @property Route[] $survivedMembers
 * @property Route[] $crossedMembers
 *
 * @method setPopulation(Route[] $population)
 * @method setSurvivedMembers(Route[] $survivedMembers)
 */
class Genetic extends AbstractAlgorithm implements GeneticAlgorithmInterface
{
    use GeneticAlgorithm {
        setUp as public geneticAlgorithmSetUp;
    }

    /** @var Route[] */
    private array $steps = [];
    private Route $result;

    public function __construct(private Graph $graph)
    {
    }

    /**
     * @throws RuntimeException
     */
    public function setUp(): void
    {
        $this->geneticAlgorithmSetUp();

        if ($this->graph->numberOfVertices < 3) {
            throw new RuntimeException('Number of vertices must be greater than 2.');
        }
    }

    /**
     * @throws Exception
     */
    public function randomizeStart(): void
    {
        for ($i = 0; $i < $this->populationSize; $i++) {
            $member = new Route($this->graph);
            $member->generateRand();
            $this->population[] = $member;
        }
    }

    public function selectionSurvivedMembersClosestToTheMinimum(): void
    {
        $this->sortRoutesByCost($this->population);
        $this->survivedMembers = array_slice($this->population, 0, ceil($this->populationSize * $this->survivalRate));
    }

    /**
     * @param Route[] $routes
     */
    public function sortRoutesByCost(array &$routes): void
    {
        usort($routes, static function (Route $a, Route $b): int {
            return $a->cost() <=> $b->cost();
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
                    $crossedMember = (new Route($this->graph, $firstMember->getCoding()));
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

    public function checkAlgorithmImproveResult(): void
    {
        $steps = count($this->steps);
        if ($steps > 2 && $this->steps[$steps-1]->cost() >= $this->steps[$steps-2]->cost()) {
            $this->iterationsWithoutImproveResult++;
        } else {
            $this->iterationsWithoutImproveResult = 0;
        }
    }

    public function saveStep(): void
    {
        $this->steps[] = $this->population[0];
    }

    /**
     * @return Route[]
     */
    public function getSteps(): array
    {
        return $this->steps;
    }

    public function saveResult(): void
    {
        $this->result = $this->population[0];
    }

    public function getResult(): Route
    {
        return $this->result;
    }

    public function result(): void
    {
        $i = 0;
        foreach ($this->steps as $step) {
            echo "$i. Route: ";
            $this->printRoute($step);
            echo " - Cost: " . $step->cost() . "\n";
            $i++;
        }
        echo 'Best route: ';
        $this->printRoute($this->result);
        echo " - Cost: " . $this->result->cost() . "\n";
    }

    private function printRoute(Route $step): void
    {
        echo implode(' - ', $step->getSequence()) . ' - ' . $step->getSequence()[0];
    }
}
