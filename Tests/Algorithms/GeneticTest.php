<?php

namespace Tests\Algorithms;

use Algorithms\Genetic;
use Representations\BinaryOfFunc;

class GeneticTest extends AbstractAlgorithmTestCase
{
    private const MEMBER_000 = '0000000000000000000000';
    private const MEMBER_010 = '0000000000000000000010';
    private const MEMBER_011 = '0000000000000000000011';
    private const MEMBER_100 = '0000000000000000000100';
    private const MEMBER_101 = '0000000000000000000101';

    /**
     * @return BinaryOfFunc[]
     */
    private function examplePopulation(Genetic $genetic): array
    {
        return [
            new BinaryOfFunc($genetic->getFunc(), self::MEMBER_000),
            new BinaryOfFunc($genetic->getFunc(), self::MEMBER_010),
            new BinaryOfFunc($genetic->getFunc(), self::MEMBER_011),
            new BinaryOfFunc($genetic->getFunc(), self::MEMBER_100),
            new BinaryOfFunc($genetic->getFunc(), self::MEMBER_101)
        ];
    }

    public function testAlgorithm(): void
    {
        // TODO: Implement testAlgorithm() method.
    }

    public function testRandomizeStart(): void
    {
        $genetic = new Genetic();
        $genetic->randomizeStart();
        $population = $genetic->getPopulation();
        foreach ($population as $member) {
            self::assertInstanceOf(BinaryOfFunc::class, $member);
            self::assertRange($genetic, $genetic->getFunc()->fByRepresentation($member));
        }
        self::assertCount($genetic->populationSize, $population);
    }

    public function testCalcMemberFX(): void
    {
        $genetic = new Genetic();
        $genetic->populationSize = 5;
        $genetic->setPopulation($this->examplePopulation($genetic));
        $genetic->calcMembersFX();
        self::assertEquals([
            0 => 0.9999999999999988,
            1 => 0.9999550592101922,
            2 => 0.9999325888635336,
            3 => 0.9999101185490533,
            4 => 0.9998876482667625,
        ],$genetic->getMemberFX());
    }

    public function testSelectionSurvivedMembersClosestToTheMinimum(): void
    {
        $genetic = new Genetic();
        $genetic->populationSize = 5;
        $genetic->setPopulation($this->examplePopulation($genetic));
        $genetic->calcMembersFX();
        $genetic->selectionSurvivedMembersClosestToTheMinimum();
        $survivedMembers = $genetic->getSurvivedMembers();
        self::assertCount(2, $survivedMembers);
        self::assertEquals(self::MEMBER_101, $survivedMembers[0]->current());
        self::assertEquals(self::MEMBER_100, $survivedMembers[1]->current());
    }

    public function testCrossMembers(): void
    {
        $genetic = new Genetic();
        $genetic->setSurvivedMembers([
            new BinaryOfFunc($genetic->getFunc(), '1111111111111111111111'),
            new BinaryOfFunc($genetic->getFunc(), '0000000000000000000000'),
        ]);
        $genetic->crossMembers();
        $crossedMembers = $genetic->getCrossedMembers();
        self::assertCount(2, $crossedMembers);
        self::assertEquals('1111111111100000000000', $crossedMembers[0]->current());
        self::assertEquals('0000000000011111111111', $crossedMembers[1]->current());
    }
}
