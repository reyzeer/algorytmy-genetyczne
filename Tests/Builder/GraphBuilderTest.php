<?php

namespace Tests\Builder;

use Builder\GraphBuilder;
use PHPUnit\Framework\TestCase;

class GraphBuilderTest extends TestCase
{
    public function testBuildEdgeGraph(): void
    {
        $file = __DIR__ . '/../Files/TSP/zeroPath.tsp';
        $builder = new GraphBuilder($file);
        $graph = $builder->build();
        self::assertEquals(10, $graph->numberOfVertices);
        self::assertEquals([3, 0, 4, 72, 36, 25, 37, 120, 2, 15], $graph->weights[0]);
        self::assertEquals([33, 25, 49, 7, 3, 0, 7, 10, 2, 1], $graph->weights[4]);
        self::assertEquals([0, 53, 44, 17, 3, 2, 7, 10, 12, 15], $graph->weights[9]);
    }

    public function testBuildVertexGraph(): void
    {
        $file = __DIR__ . '/../Files/TSP/test5vertex.tsp';
        $builder = new GraphBuilder($file);
        $graph = $builder->build();
        self::assertEquals(5, $graph->numberOfVertices);
        self::assertEquals([0.0, 5.0, 4.47213595499958, 7.0710678118654755, 8.48528137423857], $graph->weights[0]);
        self::assertEquals([5.0, 0.0, 6.082762530298219, 2.23606797749979, 3.605551275463989], $graph->weights[1]);
        self::assertEquals([4.47213595499958, 6.082762530298219, 0.0, 7.0710678118654755, 8.246211251235321], $graph->weights[2]);
        self::assertEquals([7.0710678118654755, 2.23606797749979, 7.0710678118654755, 0.0, 1.4142135623730951], $graph->weights[3]);
        self::assertEquals([8.48528137423857, 3.605551275463989, 8.246211251235321, 1.4142135623730951, 0.0], $graph->weights[4]);
    }
}
