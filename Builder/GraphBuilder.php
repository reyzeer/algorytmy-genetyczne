<?php

namespace Builder;

use Reader\TSPCoordReader;
use Representations\TSP\Graph;

class GraphBuilder
{
    private TSPCoordReader $reader;

    public function __construct(
        private string $filepath
    ) {
    }

    public function build(): Graph
    {
        $this->reader = new TSPCoordReader($this->filepath);
        $this->reader->load();
        if ($this->reader->getEdgeWeightDataType() === TSPCoordReader::VERTEX_TYPE) {
            return $this->buildVertexGraph();
        }
        if ($this->reader->getEdgeWeightDataType() === TSPCoordReader::EDGE_TYPE) {
            return $this->buildEdgeGraph();
        }
    }

    private function buildVertexGraph(): Graph
    {
        $graph = new Graph();
        $graph->numberOfVertices = count( $this->reader->nodeCoords);
        for ($i = 0; $i < $graph->numberOfVertices; $i++) {
            $graph->weights[] = [];
            for ($j = 0; $j < $graph->numberOfVertices; $j++) {
                $graph->weights[$i][$j] = $this->getDistance($i, $j);
            }
        }
        return $graph;
    }

    private function getDistance(int $i, int $j): float
    {
        $first = $this->reader->nodeCoords[$i];
        $second = $this->reader->nodeCoords[$j];
        return sqrt(($first[0] - $second[0])**2+($first[1] - $second[1])**2);
    }

    private function buildEdgeGraph(): Graph
    {
        $coords = $this->reader->nodeCoords;
        $graph = new Graph();
        $graph->numberOfVertices = count($coords);
        $graph->weights = $coords;
        return $graph;
    }

    public function printInfo(): void
    {
        echo "Graph loaded.\n" .
             "NAME: " . $this->reader->name . " \n" .
             "TYPE: " . $this->reader->type . " \n" .
             "COMMENT: " . $this->reader->comment . " \n" .
             "DIMENSION: " . $this->reader->dimension . " \n" .
             "EDGE_WEIGHT_TYPE: " . $this->reader->edgeWeightType . " \n";
    }
}
