<?php

namespace Models\Func;

class Step
{
    public function __construct(
        public float $x,
        public float $fX,
        public string $binary = ""
    ) {
    }
}
