<?php

namespace Algorithms\TSP;

use Algorithms\GeneticAlgorithm;
use Algorithms\GeneticAlgorithmInterface;
use Exception;
use Representations\TSP\Graph;
use Representations\TSP\Route;
use RuntimeException;

class Genetic implements GeneticAlgorithmInterface
{
    use GeneticAlgorithm {
        setUp as public geneticAlgorithmSetUp;
    }

    public function __construct(private Graph $graph)
    {
    }

    /**
     * @throws RuntimeException
     */
    public function setUp(): void
    {
        $this->geneticAlgorithmSetUp();

        if ($this->graph->numberOfVertices < 2) {
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

    private $steps = 1000;
    private $mutationPossibility = 0.1;
    private $numberOfRoutes = 10;

    private $routes = [];
    private $routeCosts = [];
    private $amountInNextGeneration = [];

    private $currentBestCost = PHP_INT_MAX;
    private $currentBestRoute = [];

    public function run(): void
    {
        $this->randomizeStartRoutes();
        for ($i = 0; $i < $this->steps; $i++) {
            $this->calcCosts();
            $this->calcAmountInNextGeneration();
            $this->prepareNextGeneration();
            $this->mutation();
        }
        $this->printBestRoute();
    }

    public function randomizeStartRoutes(): void
    {
        for ($i = 0; $i < $this->numberOfRoutes; $i++) {
            $this->routes[] = $this->randomizeRoute();
        }
    }

    protected function randomizeRoute(): array
    {
        $route = range(0, $this->vectors - 1);
        shuffle($route);
        return $route;
    }

    public function calcCosts(): void
    {
        foreach ($this->routes as $key => $route) {
            $this->routeCosts[$key] = $this->calcCost($route);
            if ($this->routeCosts[$key] < $this->currentBestCost) {
                $this->currentBestRoute = $route;
                $this->currentBestCost = $this->routeCosts[$key];
            }
        }
    }

    protected function calcCost(array $route): int
    {
        $cost = 0;
        $firstVector = null;
        $currentVectorIndex = null;
        foreach ($route as $currentVectorIndex) {
            $previousVector = $currentVectorIndex;
            if ($firstVector === null) {
                $firstVector = $currentVectorIndex;
                continue;
            }
            $cost += $this->getEdgeCost($previousVector, $currentVectorIndex);
        }
        $cost += $this->getEdgeCost($currentVectorIndex, $firstVector);
        return $cost;
    }

    protected function getEdgeCost(int $firstVectorIndex, int $secondVectorIndex)
    {
        return $this->graph[$firstVectorIndex][$secondVectorIndex];
    }

    protected function calcAmountInNextGeneration(): void
    {
        $sumOfCosts = array_sum($this->routeCosts);
        foreach ($this->routeCosts as $key => $value) {
            $this->amountInNextGeneration[$key] = round($this->numberOfRoutes * $value / $sumOfCosts);
        }
    }

    protected function prepareNextGeneration(): void
    {
        $nextGeneration = [];
        foreach ($this->amountInNextGeneration as $key => $amount) {
            for ($i = 0; $i < $amount; $i++) {
                $nextGeneration[] = $this->routes[$key];
            }
        }
        for ($i = 0; $i < $this->numberOfRoutes; $i += 2) {
            $this->routes[$i] = $this->crossRoute($nextGeneration[$i], $nextGeneration[$i + 1]);
            $this->routes[$i + 1] = $this->crossRoute($nextGeneration[$i + 1], $nextGeneration[$i]);
        }
    }

    protected function crossRoute(array $firstRoute, array $secondRoute): array
    {
        $newRoute = [];
        for ($i = 0; $i < $this->vectors / 2; $i++) {
            $newRoute[$i] = $firstRoute[$i];
        }
        for ($i = $this->vectors / 2; $i < $this->vectors; $i++) {
            if (!in_array($secondRoute[$i], $newRoute, true)) {
                $newRoute[] = $secondRoute[$i];
            }
        }
        foreach (range(0, $this->vectors - 1) as $vectorIndex) {
            if (!in_array($vectorIndex, $newRoute, true)) {
                $newRoute[] = $vectorIndex;
            }
        }
        return $newRoute;
    }

    /**
     * @throws Exception
     */
    public function mutation(): void
    {
        foreach ($this->routes as $key => $route) {
            if (random_int(0, 1 / $this->mutationPossibility) === 1) {
                $firstRouteElement = random_int(0, $this->vectors - 1);
                $secondRouteElement = random_int(0, $this->vectors - 1);
                $temp = $this->routes[$key][$firstRouteElement];
                $this->routes[$key][$firstRouteElement] = $this->routes[$key][$secondRouteElement];
                $this->routes[$key][$secondRouteElement] = $temp;
            }
        }
    }

    protected function printBestRoute(): void
    {
        echo "Best cost: $this->currentBestCost\n" .
            implode(' - ', $this->currentBestRoute) .
            ' - ' . $this->currentBestRoute[0];
    }

}
