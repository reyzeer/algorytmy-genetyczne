#!/usr/bin/php
<?php

use Algorithms\Func\Genetic;

require_once 'vendor/autoload.php';

$algorithm = new Genetic;
$algorithm->iterations = 10000;
$algorithm->minIterations = 6000;
$algorithm->iterationsWithoutImproveResultBeforeStop = 1000;
$algorithm->run();
$algorithm->result();
echo 'Time: ' . $algorithm->getTime() . "\n";
