#!/usr/bin/php
<?php

function f(float $x)
{
    return $x * sin( 10 * pi() * $x) + 1;
}

$rangeStart = -1;
$rangeEnd = 2;

/** --- Przeszukiwanie zupełne --- */
$delta = 0.01;
$minY = PHP_FLOAT_MAX;
$minX = 0;
for ($x = $rangeStart; $x <= $rangeEnd; $x += $delta) {
    $currentY = f($x);
    if ($currentY < $minY) {
        $minY = $currentY;
        $minX = $x;
    }
}

$currentY = f($minX);
echo "Local min from all (iterate by 0.01) | Result Y: $currentY X: $minX\n";

/** --- Lokalne minimum od początku zakresu */
$delta = 0.01;
$minY = PHP_FLOAT_MAX;
$minX = 0;
$lastY = f($rangeStart);
$currentY = f ($rangeStart);
for ($x = $rangeStart; $x <= $rangeEnd; $x += $delta) {
    $currentY = f($x);
    if ($currentY > $lastY) {
        $minY = $currentY;
        $minX = $x;
        break;
    }
}

$currentY = f($minX);
echo "Local min from range start 2 | Result Y: $currentY X: $minX\n";

/** --- Lokalne minimum od punku wyzaczone przez pochodną (x*sin(10*PI*x)+1)' */
$delta = 0.01;
$minY = PHP_FLOAT_MAX;
$minX = 0;
$lastY = f($rangeStart);
$currentY = f ($rangeStart);
for ($x = 0.25; $x <= $rangeEnd; $x += $delta) {
    $currentY = f($x);
    if ($currentY > $lastY) {
        $minY = $currentY;
        $minX = $x;
        break;
    }
}

$currentY = f($minX);
echo "Degree: Start from 0.25 | Result Y: $currentY X: $minX\n";
