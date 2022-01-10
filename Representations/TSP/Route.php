<?php

namespace Representations\TSP;

use Representations\GeneticRepresentationInterface;
use Representations\RepresentationInterface;

class Route implements RepresentationInterface, GeneticRepresentationInterface
{
    public function generateRand(): void
    {
        // TODO: Implement generateRand() method.
    }

    public function cross(GeneticRepresentationInterface $representation): void
    {
        // TODO: Implement cross() method.
    }

    public function mutation(float $mutationPossibility = 0.5): void
    {
        // TODO: Implement mutation() method.
    }
}
