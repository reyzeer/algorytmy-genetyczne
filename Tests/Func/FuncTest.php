<?php

namespace Tests\Algorithms;

use Functions\Func;
use PHPUnit\Framework\TestCase;

class FuncTest extends TestCase
{
    private Func $func;

    public function setUp(): void
    {
        parent::setUp();
        $this->func = new Func;
    }

    /**
     * @dataProvider fValuesProvider
     */
    public function testCountF(float $x, float $value): void
    {
        self::assertEqualsWithDelta($value, $this->func->f($x), 0.000001);
    }

    public function fValuesProvider(): array
    {
        return [
            [-1, 1],
            [0, 1],
            [1, 1],
            [0.123, 0.91865864056519081918205952633641414035],
            [-0.123, 0.91865864056519081918205952633641414035],
            [M_PI, -2.0326466575308070879226199264803167777],
            [12348, 1],
        ];
    }

    /**
     * @dataProvider fDerivativeValuesProvider
     */
    public function testCountFDerivative(float $x, float $value): void
    {
        self::assertEqualsWithDelta($value, $this->func->fDerivative($x), 0.000001);
    }

    public function fDerivativeValuesProvider(): array
    {
        return [
            [-1, -31.415926535897932384626433832795028842],
            [0, 0],
            [1, 31.415926535897932384626433832795028842],
            [0.123, -3.5598602789683951524052041148732497105],
            [-0.123, 3.5598602789683951524052041148732497105],
            [M_PI, -26.731335440219073942413924984856147358],
            [12348, 387923.86086526766908536720496735301614],
        ];
    }

    /**
     * @dataProvider binaryValuesProvider
     */
    public function testConvertBinaryToFloat(string $binary, float $value): void
    {
        self::assertEqualsWithDelta($value, $this->func->convertBinaryToX($binary), 0.000001);
    }

    public function binaryValuesProvider(): array
    {
        return [
            ["0000000000000000000000", -1],
            ["1000000000000000000000", 0.5],
            ["1111111111111111111111", 2]
        ];
    }

    /**
     * @dataProvider fBinaryValuesProvider
     */
    public function testGetValueByBin(string $binary, float $value): void
    {
        self::assertEqualsWithDelta($value, $this->func->fByBin($binary), 0.00001);
    }

    public function fBinaryValuesProvider(): array
    {
        return [
            ["0000000000000000000000", 1],
            ["1000000000000000000000", 1],
            ["1111111111111111111111", 1]
        ];
    }

    public function testRandX(): void
    {
        for ($i = 0; $i < 100; $i++) {
            $x = $this->func->randX();
            self::assertLessThanOrEqual($this->func->rangeEnd, $x, "Failed for x: $x");
            self::assertGreaterThanOrEqual($this->func->rangeStart, $x, "Failed for x: $x");
        }
    }
}
