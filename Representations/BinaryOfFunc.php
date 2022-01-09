<?php

namespace Representations;

use Exception;
use Functions\Func;
use Iterator;
use TypeError;

class BinaryOfFunc implements Iterator
{
    private string $startRepresentation;

    private bool $toLeft;

    public function __construct(
        private Func $func,
        private string $representation = ""
    ) {
        $this->startRepresentation = $this->representation;
    }

    public function generateRand(): void
    {
        $this->representation = "";
        for ($i = 0; $i < $this->func->bits; $i++) {
            $this->representation .= mt_rand() % 2 ? "1" : "0";
        }
        $this->startRepresentation = $this->representation;
    }

    public function current(): string
    {
        return $this->representation;
    }

    public function next(int $step = 1): void
    {
        $this->representation =  decbin(bindec($this->representation) + $step);
        $this->prependZeros();
    }

    public function prev(int $step = 1): void
    {
        $this->representation =  decbin(bindec($this->representation) - $step);
        $this->prependZeros();
    }

    private function prependZeros(): void
    {
        $zeroesToAdd = $this->func->bits - strlen($this->representation);
        $zeroesToAdd = ($zeroesToAdd < 0) ? 0 : $zeroesToAdd;
        $this->representation = str_repeat('0', $zeroesToAdd) . $this->representation;
    }

    public function key(): int
    {
        return bindec($this->representation);
    }

    public function valid(): bool
    {
        try {
            $key = $this->key();
            return $key >= 0 && $key < 2 ** $this->func->bits;
        } catch (TypeError $exception) {
            return false;
        }
    }

    public function rewind()
    {
        $this->representation = $this->startRepresentation;
    }

    /**
     * Check on which side around the point of current binary representation is minimum of function.
     * @return void
     */
    public function checkDirection(): void
    {
        $currentFX = $this->func->fByRepresentation($this);
        $this->next();
        $nextFX = $this->func->fByRepresentation($this);
        $this->toLeft = $currentFX < $nextFX;
        $this->rewind();
    }

    public function toLeft(): bool
    {
        return $this->toLeft;
    }

    public function stepToMinima(int $step = 1): void
    {
        if ($this->toLeft) {
            $this->prev($step);
        } else {
            $this->next($step);
        }
        if (!$this->valid()) {
            $this->rewind();
        }
    }

    public function backStepToMinima(int $step = 1): void
    {
        if (!$this->toLeft) {
            $this->prev($step);
        } else {
            $this->next($step);
        }
        if (!$this->valid()) {
            $this->rewind();
        }
    }

    public function cross(BinaryOfFunc $representation): void
    {
        $this->representation =
            substr($this->representation, 0, ceil($this->func->bits / 2)) .
            substr($representation->current(), 0, floor($this->func->bits / 2));
        $this->startRepresentation = $this->representation;
    }

    /**
     * @throws Exception
     */
    public function mutation(float $mutationPossibility = 0.5): void
    {
        if (random_int(1, 1 / $mutationPossibility) === 1) {
            $bitIndex = random_int(0, $this->func->bits - 1);
            $this->representation[$bitIndex] = $this->representation[$bitIndex] === '0' ? '1' : '0';
        }
        $this->startRepresentation = $this->representation;
    }
}
