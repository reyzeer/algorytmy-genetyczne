<?php

namespace Tests\Algorithms\TSP;

use Algorithms\TSP\Genetic;
use Exception;
use Representations\TSP\Graph;
use Representations\TSP\Route;
use Tests\Algorithms\AbstractAlgorithmTestCase;

class GeneticTest extends AbstractAlgorithmTestCase
{
    /**
     * @throws Exception
     */
    public function testAlgorithm()
    {
        $graph = new Graph();
        $graph->numberOfVertices = 5;
        $graph->weights = [
            [2, 4, 6, 8, 10],
            [10, 9, 8, 7, 6],
            [1, 2, 3, 4, 5],
            [2, 2, 3, 4, 4],
            [4, 4, 3, 2, 2],
        ];
        $genetic = new Genetic($graph);
        $genetic->setUp();
        $genetic->algorithm();
        $result = $genetic->getResult();
        $steps = $genetic->getSteps();
        $i = 0;
        $currentRoute = $steps[0];
        foreach ($steps as $step) {
            if ($i > 1) {
                self::assertTrue($currentRoute->cost() >= $step->cost());
            }
            $currentRoute = $step;
            $i++;
        }
        self::assertEquals($result, $currentRoute);
    }

    /**
     * @throws Exception
     */
    public function testSetUpCorrectParameters(): void
    {
        $graph = new Graph();
        $graph->numberOfVertices = 3;
        $genetic = new Genetic($graph);
        $genetic->populationSize = 10;
        $genetic->survivalRate = 0.5;
        $genetic->setUp();
        self::assertTrue(true);
    }

    /**
     * @dataProvider setUpIncorrectParametersProvider
     * @throws Exception
     */
    public function testSetUpIncorrectParameters(
        int $numberOfVertices,
        float $survivalRate,
        int $populationSize,
        string $exceptionMessage
    ): void {
        $this->expectExceptionMessage($exceptionMessage);
        $graph = new Graph();
        $graph->numberOfVertices = $numberOfVertices;
        $genetic = new Genetic($graph);
        $genetic->populationSize = $populationSize;
        $genetic->survivalRate = $survivalRate;
        $genetic->setUp();
    }

    public function setUpIncorrectParametersProvider(): array
    {
        return [
            'to low survival rate' => [
                'numberOfVertices' => 3,
                'survivalRate' => -0.1,
                'populationSize' => 10,
                'exceptionMessage' => 'Survival rate mu be greater than 0 and lower than 0.5.'
            ],
            'to high survival rate' => [
                'numberOfVertices' => 3,
                'survivalRate' => 0.51,
                'populationSize' => 10,
                'exceptionMessage' => 'Survival rate mu be greater than 0 and lower than 0.5.'
            ],
            'to low population for survival rate' => [
                'numberOfVertices' => 3,
                'survivalRate' => 0.25,
                'populationSize' => 10,
                'exceptionMessage' => 'Population size must be equal or greater than inverse of square survival rate.'
            ],
            'to low number of vertices' => [
                'numberOfVertices' => 2,
                'survivalRate' => 0.5,
                'populationSize' => 10,
                'exceptionMessage' => 'Number of vertices must be greater than 2.'
            ]
        ];
    }

    /**
     * @throws Exception
     */
    public function testRandomizeStart(): void
    {
        $graph = new Graph();
        $graph->numberOfVertices = 3;
        $genetic = new Genetic($graph);
        $genetic->randomizeStart();
        $population = $genetic->getPopulation();
        foreach ($population as $member) {
            self::assertInstanceOf(Route::class, $member);
            self::assertCount($graph->numberOfVertices, $member->getCoding());
            self::assertContains(0, $member->getSequence());
            self::assertContains(1, $member->getSequence());
            self::assertContains(2, $member->getSequence());
        }
        self::assertCount($genetic->populationSize, $population);
    }

    public function testSelectionSurvivedMembersClosestToTheMinimum(): void
    {
        $mockRoutes = $this->mockRoutes();
        $genetic = new Genetic(new Graph());
        $genetic->setPopulation($mockRoutes);
        $genetic->populationSize = count($mockRoutes);
        $genetic->survivalRate = 0.5;
        $genetic->selectionSurvivedMembersClosestToTheMinimum();
        self::assertEquals([$mockRoutes[1], $mockRoutes[2]], $genetic->getSurvivedMembers());
    }

    public function testSortRoutesByCost(): void
    {
        $mockRoutes = $this->mockRoutes();
        $genetic = new Genetic(new Graph());
        $population = $mockRoutes;
        $genetic->sortRoutesByCost($population);
        self::assertEquals([$mockRoutes[1], $mockRoutes[2], $mockRoutes[0]], $population);
    }

    private function mockRoutes(): array
    {
        $route1 = $this->createStub(Route::class);
        $route1->method('cost')->willReturn(3.0);
        $route2 = $this->createStub(Route::class);
        $route2->method('cost')->willReturn(1.0);
        $route3 = $this->createStub(Route::class);
        $route3->method('cost')->willReturn(2.0);

        return [$route1, $route2, $route3];
    }

    public function testCrossMember(): void
    {
        $graph = new Graph();
        $graph->numberOfVertices = 5;
        $genetic = new Genetic($graph);
        $genetic->setSurvivedMembers([
            new Route($graph, [0, 0, 0, 0, 0]),
            new Route($graph, [1, 1, 1, 1, 0])
        ]);
        $genetic->crossMembers();
        /** @var Route[] $crossedMembers */
        $crossedMembers = $genetic->getCrossedMembers();
        self::assertCount(2, $crossedMembers);
        self::assertEquals([0, 0, 0, 1, 0], $crossedMembers[0]->getCoding());
        self::assertEquals([1, 1, 1, 0 ,0], $crossedMembers[1]->getCoding());
    }

    /**
     * @dataProvider checkAlgorithmImproveResultProvider
     */
    public function testCheckAlgorithmImproveResult(array $steps, int $iterationsWithoutImprove): void
    {
        $graph = new Graph();
        $graph->numberOfVertices = 5;
        $genetic = new Genetic($graph);
        foreach ($steps as $step) {
            $genetic->setPopulation([$step]);
            $genetic->saveStep();
            $genetic->checkAlgorithmImproveResult();
        }
        self::assertEquals($iterationsWithoutImprove, $genetic->getIterationsWithoutImproveResult());
    }

    public function checkAlgorithmImproveResultProvider(): array
    {
        $mockRoutes = $this->mockRoutes();
        return [
            'to few elements (1)' => [
                'steps' => [
                    $mockRoutes[0]
                ],
                'iterationsWithoutImprove' => 0,
            ],
            'to few elements (2)' => [
                'steps' => [
                    $mockRoutes[0],
                    $mockRoutes[1]
                ],
                'iterationsWithoutImprove' => 0,
            ],
            '1 iterations without improve' => [
                'steps' => [
                    $mockRoutes[0],
                    $mockRoutes[0],
                    $mockRoutes[0],
                ],
                'iterationsWithoutImprove' => 1,
            ],
            '2 iterations without improve' => [
                'steps' => [
                    $mockRoutes[0],
                    $mockRoutes[0],
                    $mockRoutes[0],
                    $mockRoutes[0],
                ],
                'iterationsWithoutImprove' => 2,
            ],
            'reset iterations after improve' => [
                'steps' => [
                    $mockRoutes[0],
                    $mockRoutes[0],
                    $mockRoutes[0],
                    $mockRoutes[1],
                ],
                'iterationsWithoutImprove' => 0,
            ],
        ];
    }
}
