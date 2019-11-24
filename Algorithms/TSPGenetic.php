<?php

namespace Algorithms;

use Exception;

class TSPGenetic
{
    private $steps = 1000;
    private $mutationPossibility = 0.1;
    private $numberOfRoutes = 10;

    private $vectors = 10;
    private $graph =
        [
            [3, 5, 4, 72, 36, 25, 37, 120, 2, 15],
            [31, 5, 34, 71, 3, 2, 7, 10, 12, 15],
            [3, 25, 46, 87, 3, 2, 7, 10, 2, 1],
            [32, 15, 47, 67, 3, 2, 7, 10, 12, 15],
            [33, 25, 49, 7, 3, 2, 7, 10, 2, 1],
            [31, 58, 47, 57, 3, 2, 7, 10, 12, 5],
            [33, 35, 41, 47, 3, 2, 7, 1, 12, 15],
            [35, 54, 42, 37, 3, 2, 7, 10, 12, 1],
            [73, 25, 43, 27, 3, 2, 7, 1, 12, 15],
            [35, 53, 44, 17, 3, 2, 7, 10, 12, 15],
        ];

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