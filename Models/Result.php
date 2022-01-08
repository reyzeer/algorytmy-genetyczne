<?php

namespace Models;

use Functions\Func;
use Representations\BinaryOfFunc;

class Result extends Step
{
    public ?BinaryOfFunc $representation = null;

    public function __construct(
        public float $x,
        public float $fX,
        public string $binary = "",
        private ?Func $func = null
    ) {
        parent::__construct($x, $fX, $this->binary);
        if (!empty($this->binary)) {
            $this->representation = new BinaryOfFunc($this->func, $this->binary);
        }
    }
}
