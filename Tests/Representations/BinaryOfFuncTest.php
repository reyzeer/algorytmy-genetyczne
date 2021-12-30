<?php

namespace Representations;

use Exception;
use PHPUnit\Framework\TestCase;

class BinaryOfFuncTest extends TestCase
{
    public function testGenerateRand(): void
    {
        $representation = new BinaryOfFunc(22);

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
        $representation = new BinaryOfFunc(22, $bits);
        self::assertEquals($bits, $representation->current());
    }

    public function testNext(): void
    {
        $representation = new BinaryOfFunc(22, "1010101010101010101010");
        $representation->next();
        self::assertEquals("1010101010101010101011", $representation->current());
    }

    public function testPrev(): void
    {
        $representation = new BinaryOfFunc(22, "1010101010101010101010");
        $representation->prev();
        self::assertEquals("1010101010101010101001", $representation->current());
    }

    public function testKey(): void
    {
        $representation = new BinaryOfFunc(22, "1010101010101010101010");
        self::assertEquals(2796202, $representation->key());
    }

    public function testValidCorrectRepresentation(): void
    {
        $representation = new BinaryOfFunc(22, "1010101010101010101010");
        self::assertTrue($representation->valid());
    }

    public function testValidIncorrectRepresentation(): void
    {
        $representation = new BinaryOfFunc(22, "10101010101010101010101010");
        self::assertFalse($representation->valid());
    }

    public function testRewind(): void
    {
        $startRepresentation = "1010101010101010101010";
        $representation = new BinaryOfFunc(22, $startRepresentation);
        $representation->next();
        $representation->rewind();
        self::assertEquals($startRepresentation, $representation->current());
    }
}