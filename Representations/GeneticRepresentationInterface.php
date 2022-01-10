<?php

namespace Representations;

interface GeneticRepresentationInterface
{
    public function cross(GeneticRepresentationInterface $representation): void;

    public function mutation(float $mutationPossibility = 0.5): void;
}
