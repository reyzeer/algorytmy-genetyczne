<?php

namespace Representations\TSP;

use Exception;
use Representations\GeneticRepresentationInterface;
use Representations\RepresentationInterface;

class Route implements RepresentationInterface, GeneticRepresentationInterface, TSPRepresentationInterface
{
    /**
     * @param int[] $coding
     */
    public function __construct(
        private Graph $graph,
        private array $coding = []
    ) {
    }

    /**
     * @throws Exception
     */
    public function generateRand(): void
    {
        $this->coding = [];
        for ($i = $this->graph->numberOfVertices - 1; $i >= 0; $i--) {
            $this->coding[] = random_int(0, $i);
        }
    }

    public function cost(): float
    {
        $cost = 0;
        $sequence = $this->getSequence();
        for ($i = 0, $iMax = count($sequence); $i < $iMax - 1; $i++) {
            $cost += $this->graph->getEdge($sequence[$i], $sequence[$i + 1]);
        }
        $cost += $this->graph->getEdge($sequence[$i], $sequence[0]);
        return $cost;
    }

    public function getSequence(): array
    {
        $vertices = range(0, $this->graph->numberOfVertices - 1);
        $sequence = [];
        foreach ($this->coding as $element) {
            $sequence[] = $vertices[$element];
            unset($vertices[$element]);
            $vertices = array_values($vertices);
        }
        return $sequence;
    }

    public function cross(GeneticRepresentationInterface|Route $representation): void
    {
        $this->coding = array_merge(
            array_slice($this->coding, 0, ceil($this->graph->numberOfVertices / 2)),
            array_slice($representation->getCoding(), ceil($this->graph->numberOfVertices / 2))
        );
    }

    /**
     * @throws Exception
     */
    public function mutation(float $mutationPossibility = 0.5): void
    {
        if (random_int(1, 1 / $mutationPossibility) === 1) {
            $elementIndex = random_int(0, $this->graph->numberOfVertices - 2); // last element can only be 0, last element of array is n-1  (-1-1=-2)
            do {
                $newElement = random_int(0, $this->graph->numberOfVertices - $elementIndex - 1);
            } while ($this->coding[$elementIndex] === $newElement); // must be new value
            $this->coding[$elementIndex] = $newElement;
        }
    }

    public function getCoding(): array
    {
        return $this->coding;
    }
}
