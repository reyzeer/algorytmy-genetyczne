<?php

namespace Tests\Algorithms\Func;

use Algorithms\Func\Genetic;
use Exception;
use Models\Func\Step;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;
use Representations\Func\Binary;

class GeneticTest extends AbstractFuncAlgorithmTestCase
{
    private const MEMBER_000 = '0000000000000000000000';
    private const MEMBER_010 = '0000000000000000000010';
    private const MEMBER_011 = '0000000000000000000011';
    private const MEMBER_100 = '0000000000000000000100';
    private const MEMBER_101 = '0000000000000000000101';

    /**
     * @return Binary[]
     */
    private function examplePopulation(Genetic $genetic): array
    {
        return [
            new Binary($genetic->getFunc(), self::MEMBER_000),
            new Binary($genetic->getFunc(), self::MEMBER_010),
            new Binary($genetic->getFunc(), self::MEMBER_011),
            new Binary($genetic->getFunc(), self::MEMBER_100),
            new Binary($genetic->getFunc(), self::MEMBER_101)
        ];
    }

    /**
     * @throws Exception
     */
    public function testAlgorithm(): void
    {
        $genetic = new Genetic();
        $genetic->setUp();
        $genetic->algorithm();
        $result = $genetic->getResult();
        self::assertRange($genetic, $result->x);
        $steps = $genetic->getSteps();
        $currentStep = new Step(PHP_FLOAT_MAX, PHP_FLOAT_MAX);
        foreach ($steps as $step) {
            self::assertTrue($currentStep->fX >= $step->fX);
            $currentStep = $step;
        }
    }

    /**
     * @throws Exception
     */
    public function testSetUpCorrectParameters(): void
    {
        $genetic = new Genetic();
        $genetic->populationSize = 10;
        $genetic->survivalRate = 0.5;
        $genetic->setUp();
        self::assertTrue(true);
    }

    /**
     * @dataProvider setUpIncorrectParametersProvider
     * @throws Exception
     */
    public function testSetUpIncorrectParameters(float $survivalRate, int $populationSize, string $exceptionMessage): void
    {
        $this->expectExceptionMessage($exceptionMessage);
        $genetic = new Genetic();
        $genetic->populationSize = $populationSize;
        $genetic->survivalRate = $survivalRate;
        $genetic->setUp();
    }

    public function setUpIncorrectParametersProvider(): array
    {
        return [
            'to low survival rate' => [
                'survivalRate' => -0.1,
                'populationSize' => 10,
                'exceptionMessage' => 'Survival rate mu be greater than 0 and lower than 0.5.',
            ],
            'to high survival rate' => [
                'survivalRate' => 0.51,
                'populationSize' => 10,
                'exceptionMessage' => 'Survival rate mu be greater than 0 and lower than 0.5.',
            ],
            'to low population for survival rate' => [
                'survivalRate' => 0.25,
                'populationSize' => 10,
                'exceptionMessage' => 'Population size must be equal or greater than inverse of square survival rate.',
            ]
        ];
    }

    public function testRandomizeStart(): void
    {
        $genetic = new Genetic();
        $genetic->randomizeStart();
        $population = $genetic->getPopulation();
        foreach ($population as $member) {
            self::assertInstanceOf(Binary::class, $member);
            self::assertRange($genetic, $genetic->getFunc()->convertRepresentationToX($member));
        }
        self::assertCount($genetic->populationSize, $population);
    }

    public function testSortRepresentationsByF(): void
    {
        $genetic = new Genetic();
        $population = $this->examplePopulation($genetic);
        $genetic->sortRepresentationsByF($population);
        self::assertEquals(self::MEMBER_101, $population[0]->current());
        self::assertEquals(self::MEMBER_100, $population[1]->current());
        self::assertEquals(self::MEMBER_011, $population[2]->current());
        self::assertEquals(self::MEMBER_010, $population[3]->current());
        self::assertEquals(self::MEMBER_000, $population[4]->current());
    }

    /**
     * @throws Exception
     */
    public function testSelectionSurvivedMembersClosestToTheMinimum(): void
    {
        $genetic = new Genetic();
        $genetic->populationSize = 5;
        $genetic->survivalRate = 0.5;
        $genetic->setUp();
        $genetic->setPopulation($this->examplePopulation($genetic));
        $genetic->selectionSurvivedMembersClosestToTheMinimum();
        $survivedMembers = $genetic->getSurvivedMembers();
        self::assertCount(3, $survivedMembers);
        self::assertEquals(self::MEMBER_101, $survivedMembers[0]->current());
        self::assertEquals(self::MEMBER_100, $survivedMembers[1]->current());
        self::assertEquals(self::MEMBER_011, $survivedMembers[2]->current());
    }

    public function testCrossMembers(): void
    {
        $genetic = new Genetic();
        $genetic->setSurvivedMembers([
            new Binary($genetic->getFunc(), '1111111111111111111111'),
            new Binary($genetic->getFunc(), '0000000000000000000000'),
        ]);
        $genetic->crossMembers();
        $crossedMembers = $genetic->getCrossedMembers();
        self::assertCount(2, $crossedMembers);
        self::assertEquals('1111111111100000000000', $crossedMembers[0]->current());
        self::assertEquals('0000000000011111111111', $crossedMembers[1]->current());
    }

    /**
     * @throws Exception
     */
    public function testPrepareNextGeneration(): void
    {
        $genetic = new Genetic();
        $genetic->populationSize = 20;
        $genetic->survivalRate = 0.25;
        $genetic->setUp();
        $genetic->randomizeStart();
        $genetic->selectionSurvivedMembersClosestToTheMinimum();
        $genetic->crossMembers();
        $genetic->prepareNextGeneration();
        self::assertCount($genetic->populationSize, $genetic->getPopulation());
    }

    /**
     * @throws Exception
     */
    public function testMutation(): void
    {
        $binaryOfFunc = $this
            ->getMockBuilder(Binary::class)
            ->disableOriginalConstructor()
            ->getMock();
        $binaryOfFunc->expects($this->exactly(2))->method('mutation');
        $genetic = new Genetic();
        $genetic->setPopulation([
            $binaryOfFunc,
            $binaryOfFunc,
            $binaryOfFunc
        ]);
        $genetic->mutation();
    }

    /**
     * @dataProvider checkAlgorithmImproveResultProvider
     */
    public function testCheckAlgorithmImproveResult(array $steps, int $iterationsWithoutImprove): void
    {
        $genetic = new Genetic();
        $func = $genetic->getFunc();
        foreach ($steps as $step) {
            $genetic->setPopulation([new Binary($func, $step)]);
            $genetic->saveStep();
            $genetic->checkAlgorithmImproveResult();
        }
        self::assertEquals($iterationsWithoutImprove, $genetic->getIterationsWithoutImproveResult());
    }

    public function checkAlgorithmImproveResultProvider(): array
    {
        return [
            'to few elements (1)' => [
                'steps' => [
                    self::MEMBER_101
                ],
                'iterationsWithoutImprove' => 0,
            ],
            'to few elements (2)' => [
                'steps' => [
                    self::MEMBER_100,
                    self::MEMBER_101
                ],
                'iterationsWithoutImprove' => 0,
            ],
            '1 iterations without improve' => [
                'steps' => [
                    self::MEMBER_101,
                    self::MEMBER_101,
                    self::MEMBER_101,
                ],
                'iterationsWithoutImprove' => 1,
            ],
            '2 iterations without improve' => [
                'steps' => [
                    self::MEMBER_101,
                    self::MEMBER_101,
                    self::MEMBER_101,
                    self::MEMBER_101,
                ],
                'iterationsWithoutImprove' => 2,
            ],
            'reset iterations after improve' => [
                'steps' => [
                    self::MEMBER_100,
                    self::MEMBER_100,
                    self::MEMBER_100,
                    self::MEMBER_101,
                ],
                'iterationsWithoutImprove' => 0,
            ],
        ];
    }

    /**
     * @dataProvider checkAlgorithmStoppedImproveResultProvider
     */
    public function testCheckAlgorithmStoppedImproveResult(
        int $i,
        int $minIterations,
        int $iterationsWithoutImproveResult,
        int $iterationsWithoutImproveResultBeforeStop,
        bool $stopped
    ): void {
        $genetic = new Genetic();

        $reflector = new ReflectionProperty(Genetic::class, 'i');
        $reflector->setAccessible(true);
        $reflector->setValue($genetic, $i);

        $reflector = new ReflectionProperty(Genetic::class, 'iterationsWithoutImproveResult');
        $reflector->setAccessible(true);
        $reflector->setValue($genetic, $iterationsWithoutImproveResult);

        $genetic->minIterations = $minIterations;
        $genetic->iterationsWithoutImproveResultBeforeStop = $iterationsWithoutImproveResultBeforeStop;

        self::assertEquals($stopped, $genetic->checkAlgorithmStoppedImproveResult());
    }

    public function checkAlgorithmStoppedImproveResultProvider(): array
    {
        return [
            'i < minIterations' => [
                'i' => 5,
                'minIterations' => 10,
                'iterationsWithoutImproveResult' => 5,
                'iterationsWithoutImproveResultBeforeStop' => 3,
                'stopped' => false
            ],
            'i == minIterations' => [
                'i' => 10,
                'minIterations' => 10,
                'iterationsWithoutImproveResult' => 5,
                'iterationsWithoutImproveResultBeforeStop' => 3,
                'stopped' => false
            ],
            'to much results without improve, not equal' => [
                'i' => 11,
                'minIterations' => 10,
                'iterationsWithoutImproveResult' => 5,
                'iterationsWithoutImproveResultBeforeStop' => 3,
                'stopped' => true
            ],
            'to much results without improve, equal' => [
                'i' => 11,
                'minIterations' => 10,
                'iterationsWithoutImproveResult' => 3,
                'iterationsWithoutImproveResultBeforeStop' => 3,
                'stopped' => true
            ],
            'result still fresh' => [
                'i' => 11,
                'minIterations' => 10,
                'iterationsWithoutImproveResult' => 3,
                'iterationsWithoutImproveResultBeforeStop' => 5,
                'stopped' => false
            ],
        ];
    }
}
