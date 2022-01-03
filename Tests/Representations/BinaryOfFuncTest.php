<?php

namespace Representations;

use Functions\Func;
use PHPUnit\Framework\TestCase;

class BinaryOfFuncTest extends TestCase
{
    public function testGenerateRand(): void
    {
        $representation = new BinaryOfFunc(new Func());

        for ($i = 0; $i < 100; $i++) {
            $representation->generateRand();
            self::assertLessThanOrEqual(2**22, $representation->key());
            self::assertGreaterThanOrEqual(0, $representation->key());
            self::assertEquals(1,
                preg_match('/[0-1]{22}/', $representation->current())
            );
        }
    }

    public function testCurrent(): void
    {
        $bits = "1010101010101010101010";
        $representation = new BinaryOfFunc(new Func(), $bits);
        self::assertEquals($bits, $representation->current());
    }

    public function testNext(): void
    {
        $representation = new BinaryOfFunc(new Func(), "1010101010101010101010");
        $representation->next();
        self::assertEquals("1010101010101010101011", $representation->current());
    }

    public function testPrev(): void
    {
        $representation = new BinaryOfFunc(new Func(), "1010101010101010101010");
        $representation->prev();
        self::assertEquals("1010101010101010101001", $representation->current());
    }

    public function testKey(): void
    {
        $representation = new BinaryOfFunc(new Func(), "1010101010101010101010");
        self::assertEquals(2796202, $representation->key());
    }

    /**
     * @dataProvider validProvider
     */
    public function testValid(string $binary, bool $correct): void
    {
        $representation = new BinaryOfFunc(new Func(), $binary);
        self::assertEquals($correct, $representation->valid());
    }

    public function validProvider(): array
    {
        return [
            'correct representation' => [
                'binary' => '1010101010101010101010',
                'correct' => true,
            ],
            'incorrect representation' => [
                'binary' => '10101010101010101010101010',
                'correct' => false,
            ]
        ];
    }

    public function testRewind(): void
    {
        $startRepresentation = "1010101010101010101010";
        $representation = new BinaryOfFunc(new Func(), $startRepresentation);
        $representation->next();
        $representation->rewind();
        self::assertEquals($startRepresentation, $representation->current());
    }

    /**
     * @dataProvider checkDirectionProvider
     */
    public function testCheckDirection(string $binary, bool $onLeft): void
    {
        $representation = new BinaryOfFunc(new Func(), $binary);
        $representation->checkDirection();
        self::assertEquals($onLeft, $representation->toLeft());
        self::assertEquals($binary, $representation->current());
    }

    public function checkDirectionProvider(): array
    {
        return [
            'minima on left' => [
                'binary' => '1010101010101010101010',
                'onLeft' => true
            ],
            'minima on right' => [
                'binary' => '0001111010101010101010',
                'onLeft' => false
            ]
        ];
    }

    /**
     * @dataProvider stepToMinimumProvider
     */
    public function testStepToMinima(string $beforeStep, string $afterStep): void
    {
        $representation = new BinaryOfFunc(new Func(), $beforeStep);
        $representation->checkDirection();
        $representation->stepToMinima();
        self::assertEquals($afterStep, $representation->current());
    }

    public function stepToMinimumProvider(): array
    {
        return [
            'minima on left' => [
                'beforeStep' => '1010101010101010101010',
                'afterStep'  => '1010101010101010101001',
            ],
            'minima on right' => [
                'beforeStep' => '0001111010101010101010',
                'afterStep'  => '0001111010101010101011',
            ]
        ];
    }

    /**
     * @dataProvider backStepToMinimaProvider
     */
    public function testBackStepToMinimumOnLeft(string $binary): void
    {
        $representation = new BinaryOfFunc(new Func(), $binary);
        $representation->checkDirection();
        $representation->stepToMinima();
        $representation->backStepToMinima();
        self::assertEquals($binary, $representation->current());
    }

    public function backStepToMinimaProvider(): array
    {
        return [
            'minima on left' => [
                'binary' => '0001111010101010101010'
            ],
            'minima on right' => [
                'binary' => '1010101010101010101010'
            ]
        ];
    }
}