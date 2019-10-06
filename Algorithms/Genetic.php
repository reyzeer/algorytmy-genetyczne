<?php

namespace Algorithms;

use Exception;

class Genetic
{
    private $function = null;
    private $startRange = -1;
    private $endRange = 2;
    private $bits = 22;

    private $numberOfSubjects = 10;
    private $subjects = [];

    public function __construct()
    {
        $this->function = static function($x) {
            return $x * sin(10 * M_PI * $x) + 1;
        };
    }

    public function run(): void
    {
        $this->randomizeStart();
    }

    protected function getValue(string $binary): float
    {
        return ($this->function)(abs
            ($this->startRange - $this->endRange) * bindec($binary)/(2**$this->bits - 1) - 1);
    }


    /**
     * @throws Exception
     */
    protected function randomizeStart(): void
    {
        for ($i = 0; $i < $this->numberOfSubjects; $i++) {
            $subject = '';
            for ($j = 0 ; $j < $this->bits; $j++) {
                $subject .= random_int(0, 1) === 0 ? '1' : '0';
            }
        }
    }
}
