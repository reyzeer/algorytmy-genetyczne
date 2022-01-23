#!/usr/bin/php
<?php

use Algorithms\TSP\Genetic;
use Builder\GraphBuilder;

require_once 'vendor/autoload.php';

//$builder = new GraphBuilder(__DIR__ . '/Files/TSP/zeroPath.tsp');
$builder = new GraphBuilder(__DIR__ . '/Files/TSP/otherPaths.tsp');
//$builder = new GraphBuilder(__DIR__ . '/Files/TSP/zeroPath.tsp');
//$builder = new GraphBuilder(__DIR__ . '/Files/TSP/ch150.tsp');

$graph = $builder->build();
$builder->printInfo();

$algorithm = new Genetic($graph);
$algorithm->iterations = 100000;
$algorithm->minIterations = 10000;
$algorithm->iterationsWithoutImproveResultBeforeStop = 1000;

$algorithm->run();
$algorithm->result();

echo 'Time: ' . $algorithm->getTime() . "\n";
