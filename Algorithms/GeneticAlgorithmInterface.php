<?php

namespace Algorithms;

interface GeneticAlgorithmInterface
{
    public function randomizeStart(): void;

    public function selectionSurvivedMembersClosestToTheMinimum(): void;

    public function crossMembers(): void;

    public function prepareNextGeneration(): void;

    public function mutation(): void;
}
