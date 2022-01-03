<?php

namespace Representations;

use Functions\Func;
use Iterator;

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

    public function next(): void
    {
        $this->representation =  decbin(bindec($this->representation) + 1);
        $this->prependZeros();
    }

    public function prev(): void
    {
        $this->representation =  decbin(bindec($this->representation) - 1);
        $this->prependZeros();
    }

    private function prependZeros(): void
    {
        $this->representation =
            str_repeat('0', $this->func->bits - strlen($this->representation)) .
            $this->representation;
    }

    public function key(): int
    {
        return bindec($this->representation);
    }

    public function valid(): bool
    {
        $key = $this->key();
        return $key >= 0 && $key < 2**$this->func->bits;
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

    public function stepToMinima(): void
    {
        if ($this->toLeft) {
            $this->prev();
        } else {
            $this->next();
        }
    }

    public function backStepToMinima(): void
    {
        if (!$this->toLeft) {
            $this->prev();
        } else {
            $this->next();
        }
    }
}