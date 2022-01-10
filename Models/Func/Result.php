<?php

namespace Models\Func;

use Functions\Func;
use Representations\Func\Binary;

class Result extends Step
{
    public ?Binary $representation = null;

    public function __construct(
        public float $x,
        public float $fX,
        public string $binary = "",
        private ?Func $func = null
    ) {
        parent::__construct($x, $fX, $this->binary);
        if (!empty($this->binary)) {
            $this->representation = new Binary($this->func, $this->binary);
        }
    }
}
