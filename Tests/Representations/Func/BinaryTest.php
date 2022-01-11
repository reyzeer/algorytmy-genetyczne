<?php

namespace Representations\Func;

use Exception;
use Functions\Func;
use PHPUnit\Framework\TestCase;

class BinaryTest extends TestCase
{
    public function testGenerateRand(): void
    {
        $representation = new Binary(new Func());

        for ($i = 0; $i < 100; $i++) {
            $representation->generateRand();
            self::assertLessThanOrEqual(2**22, $representation->key());
            self::assertGreaterThanOrEqual(0, $representation->key());
            self::assertMatchesRegularExpression('/[0-1]{22}/', $representation->current());
        }
    }

    public function testCurrent(): void
    {
        $bits = "1010101010101010101010";
        $representation = new Binary(new Func(), $bits);
        self::assertEquals($bits, $representation->current());
    }

    /**
     * @dataProvider nextProvider
     */
    public function testNext(?int $step, string $before, string $after): void
    {
        $representation = new Binary(new Func(), $before);
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
        $representation = new Binary(new Func(), $before);
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
        $representation = new Binary(new Func(), "1010101010101010101010");
        self::assertEquals(2796202, $representation->key());
    }

    /**
     * @dataProvider validProvider
     */
    public function testValid(string $binary, bool $correct): void
    {
        $representation = new Binary(new Func(), $binary);
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
        $representation = new Binary(new Func(), $startRepresentation);
        $representation->next();
        $representation->rewind();
        self::assertEquals($startRepresentation, $representation->current());
    }

    /**
     * @dataProvider checkDirectionProvider
     */
    public function testCheckDirection(string $binary, bool $onLeft): void
    {
        $representation = new Binary(new Func(), $binary);
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
        $representation = new Binary(new Func(), $beforeStep);
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
        $representation = new Binary(new Func(), $binary);
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

    /**
     * @dataProvider crossProvider
     */
    public function testCross(int $bits, string $first, string $second, string $result): void
    {
        $func = new Func();
        $func->bits = $bits;
        $firstRepresentation = new Binary($func, $first);
        $secondRepresentation = new Binary($func, $second);
        $firstRepresentation->cross($secondRepresentation);
        self::assertEquals($result, $firstRepresentation->current());
        $firstRepresentation->rewind();
        self::assertEquals($result, $firstRepresentation->current());
    }

    public function crossProvider(): array
    {
        return [
            'even' => [
                'bits' => 22,
                'first'  => '1111111111111111111111',
                'second' => '0000000000000000000000',
                'result' => '1111111111100000000000'
            ],
            'odd' => [
                'bits' => 23,
                'first'  => '00000000000000000000000',
                'second' => '11111111111111111111111',
                'result' => '00000000000011111111111'
            ],
            'specific design' => [
                'bits' => 22,
                'first'  => '1010101010101010101010',
                'second' => '0101010101010101010101',
                'result' => '1010101010110101010101'
            ],
        ];
    }

    /**
     * @dataProvider mutationProvider
     * @throws Exception
     */
    public function testMutation(string $binary): void
    {
        $func = new Func();
        $representation = new Binary($func, $binary);
        $representation->mutation(1);
        $result = $representation->current();
        $mutatedBits = 0;
        for ($i = 0, $iMax = strlen($binary); $i < $iMax; $i++) {
            if ($binary[$i] !== $result[$i]) {
                $mutatedBits++;
            }
        }
        self::assertEquals(1, $mutatedBits);
        $representation->rewind();
        self::assertEquals($result, $representation->current());
    }

    public function mutationProvider(): array
    {
        return [
            'mutation 0 to 1' => [
                'binary' => '0000000000000000000000',
            ],
            'mutation 1 to 0' => [
                'binary' => '1111111111111111111111',
            ],
        ];
    }

    /**
     * @dataProvider fValueProvider
     */
    public function testFValue(string $binary, float $fValue): void
    {
        $func = new Func();
        $representation = new Binary($func, $binary);
        self::assertEquals($fValue, $representation->fValue());
    }

    public function fValueProvider(): array
    {
        return [
            [
                'binary' => "0000000000000000000000",
                'fValue' => 0.9999999999999988
            ],
            [
                'binary' => "1000000000000000000000",
                'fValue' => 0.99999438238922
            ],
            [
                'binary' => "1111111111111111111111",
                'fValue' => 1
            ],
        ];
    }
}
