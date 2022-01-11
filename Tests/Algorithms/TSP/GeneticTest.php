<?php

namespace Tests\Algorithms\TSP;

use Algorithms\TSP\Genetic;
use Exception;
use Representations\Func\Binary;
use Representations\TSP\Graph;
use Representations\TSP\Route;
use Tests\Algorithms\AbstractAlgorithmTestCase;

class GeneticTest extends AbstractAlgorithmTestCase
{
    public function testAlgorithm()
    {
        // TODO: Implement testAlgorithm() method.
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
            self::assertContains($member->getCoding(), [0, 1, 2]);
        }
        self::assertCount($genetic->populationSize, $population);
    }
}
