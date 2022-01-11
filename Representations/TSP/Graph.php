<?php

namespace Representations\TSP;

class Graph
{
    public int $numberOfVertices;
    /** @var float[][] */
    public array $weights = [];

    public function getEdge(int $start, int $end): float
    {
        return $this->weights[$start][$end];
    }
}
