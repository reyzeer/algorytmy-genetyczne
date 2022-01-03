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

    /**
     * @dataProvider nextProvider
     */
    public function testNext(?int $step, string $before, string $after): void
    {
        $representation = new BinaryOfFunc(new Func(), $before);
        if ($step) {
            $representation->next($step);
        } else {
            $representation->next();
        }
        self::assertEquals($after, $representation->current());
    }

    public function nextProvider(): array
    {
        return [
            'standard +1' => [
                'step' => null,
                'before' => '1010101010101010101010',
                'after' => '1010101010101010101011'
            ],
            '+2' => [
                'step' => 2,
                'before' => '1010101010101010101010',
                'after' => '1010101010101010101100'
            ],
            'out of range' => [
                'step' => 10,
                'before' => '1111111111111111111111',
                'after' => '10000000000000000001001',
            ]
        ];
    }

    /**
     * @dataProvider prevProvider
     */
    public function testPrev(?int $step, string $before, string $after): void
    {
        $representation = new BinaryOfFunc(new Func(), $before);
        if ($step) {
            $representation->prev($step);
        } else {
            $representation->prev();
        }
        self::assertEquals($after, $representation->current());
    }

    public function prevProvider(): array
    {
        return [
            'standard -1' => [
                'step' => null,
                'before' => '1010101010101010101010',
                'after' => '1010101010101010101001'
            ],
            '-2' => [
                'step' => 2,
                'before' => '1010101010101010101010',
                'after' => '1010101010101010101000'
            ],
            'out of range' => [
                'step' => 10,
                'before' => '0000000000000000000000',
                'after' => '1111111111111111111111111111111111111111111111111111111111110110',
            ]
        ];
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
    public function testStepToMinima(?int $step, string $beforeStep, string $afterStep): void
    {
        $representation = new BinaryOfFunc(new Func(), $beforeStep);
        $representation->checkDirection();
        if ($step) {
            $representation->stepToMinima($step);
        } else {
            $representation->stepToMinima();
        }
        self::assertEquals($afterStep, $representation->current());
    }

    public function stepToMinimumProvider(): array
    {
        return [
            'minima on left - standard step +1' => [
                'step' => null,
                'beforeStep' => '1010101010101010101010',
                'afterStep'  => '1010101010101010101001',
            ],
            'minima on left - step +2' => [
                'step' => 2,
                'beforeStep' => '1010101010101010101010',
                'afterStep'  => '1010101010101010101000',
            ],
            'minima on right - standard step +1' => [
                'step' => null,
                'beforeStep' => '0001111010101010101010',
                'afterStep'  => '0001111010101010101011',
            ],
            'minima on right - step +2' => [
                'step' => 2,
                'beforeStep' => '0001111010101010101010',
                'afterStep'  => '0001111010101010101100',
            ]
        ];
    }

    /**
     * @dataProvider backStepToMinimaProvider
     */
    public function testBackStepToMinimumOnLeft(?int $step, string $binary): void
    {
        $representation = new BinaryOfFunc(new Func(), $binary);
        $representation->checkDirection();
        if ($step) {
            $representation->stepToMinima($step);
            $representation->backStepToMinima($step);
        } else {
            $representation->stepToMinima();
            $representation->backStepToMinima();
        }
        self::assertEquals($binary, $representation->current());
    }

    public function backStepToMinimaProvider(): array
    {
        return [
            'minima on left - standard step + 1' => [
                'step' => null,
                'binary' => '0001111010101010101010'
            ],
            'minima on left - step + 2' => [
                'step' => 2,
                'binary' => '0001111010101010101010'
            ],
            'minima on right - standard step +1' => [
                'step' => null,
                'binary' => '1010101010101010101010'
            ],
            'minima on right - step +2' => [
                'step' => 2,
                'binary' => '1010101010101010101010'
            ]
        ];
    }
}