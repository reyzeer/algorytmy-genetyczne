<?php

namespace Representations\TSP;

use Exception;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertTrue;

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
}
