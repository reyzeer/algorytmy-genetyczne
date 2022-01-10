<?php

namespace Representations\TSP;

use Exception;
use Representations\GeneticRepresentationInterface;
use Representations\RepresentationInterface;

class Route implements RepresentationInterface, GeneticRepresentationInterface
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

    public function cross(GeneticRepresentationInterface $representation): void
    {
        // TODO: Implement cross() method.
    }

    public function mutation(float $mutationPossibility = 0.5): void
    {
        // TODO: Implement mutation() method.
    }

    public function getCoding(): array
    {
        return $this->coding;
    }
}
