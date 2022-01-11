<?php

namespace Representations\TSP;

use Exception;
use Functions\Func;
use PHPUnit\Framework\TestCase;
use Representations\Func\Binary;

class RouteTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testGenerateRand(): void
    {
        $graph = new Graph();
        $graph->numberOfVertices = 5;
        $route = new Route($graph);
        for ($i = 0; $i < 100; $i++) {
            $route->generateRand();
            $coding = $route->getCoding();
            for ($j = 0; $j < $graph->numberOfVertices; $j++) {
                self::assertIsInt($coding[$j]);
                self::assertTrue($coding[$j] >= 0 && $coding[$j] < ($graph->numberOfVertices - $j));
            }
        }
    }

    /**
     * @dataProvider sequenceProvider
     */
    public function testSequence(array $coding, array $sequence): void
    {
        $graph = new Graph();
        $graph->numberOfVertices = 5;
        $route = new Route($graph, $coding);
        self::assertEquals($sequence, $route->getSequence());
    }

    public function sequenceProvider(): array
    {
        return [
            [
                'coding' => [4, 3, 2, 1, 0],
                'sequence' => [4, 3, 2, 1, 0]
            ],
            [
                'coding' => [0, 0, 0, 0, 0],
                'sequence' => [0, 1, 2, 3, 4]
            ],
            [
                'coding' => [1, 1, 2, 0, 0],
                'sequence' => [1, 2, 4, 0, 3]
            ],
        ];
    }

    /**
     * @dataProvider costProvider
     */
    public function testCost(array $coding, float $cost): void
    {
        $graph = new Graph();
        $graph->numberOfVertices = 5;
        $graph->weights = [
            [1, 2, 3, 4, 5],
            [6, 7, 8, 9, 10],
            [11, 12, 13, 14, 15],
            [16, 17, 18, 19, 20],
            [21, 22, 23, 24, 25]
        ];
        $route = new Route($graph, [4, 3, 2, 1, 0]);
        self::assertEquals($cost, $route->cost());
    }

    public function costProvider(): array
    {
        return [
            [
                'coding' => [4, 3, 2, 1, 0],
                'cost' => 65
            ],
            [
                'coding' => [0, 0, 0, 0, 0],
                'cost' => 65
            ],
            [
                'coding' => [1, 1, 2, 0, 0],
                'cost' => 65
            ]
        ];
    }

    /**
     * @dataProvider crossProvider
     */
    public function testCross(int $numberOfVertices, array $first, array $second, array $result): void
    {
        $graph = new Graph();
        $graph->numberOfVertices = $numberOfVertices;
        $first = new Route($graph, $first);
        $second = new Route($graph, $second);
        $first->cross($second);
        self::assertEquals($result, $first->getCoding());
    }

    public function crossProvider(): array
    {
        return [
            'even' => [
                'numberOfVertices' => 5,
                'first'  => [4, 3, 2, 0, 0],
                'second' => [1, 1, 1, 1, 0],
                'result' => [4, 3, 2, 1, 0]
            ],
            'odd' => [
                'numberOfVertices' => 6,
                'first'  => [0, 0, 0, 0, 0, 0],
                'second' => [1, 1, 1, 1, 1, 0],
                'result' => [0, 0, 0, 1, 1, 0]
            ]
        ];
    }

    /**
     * @dataProvider mutationProvider
     * @throws Exception
     */
    public function testMutation(array $coding): void
    {
        $graph = new Graph();
        $graph->numberOfVertices = 5;
        $route = new Route($graph, $coding);
        $route->mutation(1);
        $result = $route->getCoding();
        $elementId = -1;
        $elementValue = -1;
        for ($i = 0; $i < $graph->numberOfVertices; $i++) {
            if ($coding[$i] !== $result[$i]) {
                $elementId = $i;
                $elementValue = $result[$i];
            }
        }
        self::assertNotSame(-1, $elementId);
        self::assertNotSame(-1, $elementValue);
        self::assertLessThan($graph->numberOfVertices - $elementId, $elementValue);
    }

    public function mutationProvider(): array
    {
        return [
            'mutation 0 to other' => [
                'coding' => [0, 0, 0, 0, 0],
            ],
            [
                'coding' => [4, 3, 2, 1, 0],
            ],
            [
                'coding' => [0, 0, 0, 0, 0],
            ],
            [
                'coding' => [1, 1, 2, 0, 0],
            ]
        ];
    }

    /**
     * @dataProvider mutationProvider
     * @throws Exception
     */
    public function testSomeRunningMutation(array $coding): void
    {
        for ($i = 0; $i < 100; $i++) {
            $this->testMutation($coding);
        }
    }
}
