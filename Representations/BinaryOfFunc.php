<?php

namespace Representations;

use Exception;
use Iterator;

class BinaryOfFunc implements Iterator
{
    private string $startRepresentation;

    public function __construct(
        private int $bits,
        private string $representation = ""
    ) {
        $this->startRepresentation = $this->representation;
    }

    public function generateRand(): void
    {
        $this->representation = "";
        for ($i = 0; $i < $this->bits; $i++) {
            $this->representation .= mt_rand() % 2 ? "1" : "0";
        }
    }

    public function current(): string
    {
        return $this->representation;
    }

    public function next(): void
    {
        $this->representation =  decbin(bindec($this->representation) + 1);
    }

    public function prev(): void
    {
        $this->representation =  decbin(bindec($this->representation) - 1);
    }

    public function key(): int
    {
        return bindec($this->representation);
    }

    public function valid(): bool
    {
        $key = $this->key();
        return $key >= 0 && $key < 2**$this->bits;
    }

    public function rewind()
    {
        $this->representation = $this->startRepresentation;
    }
}