#!/usr/bin/php
<?php

use Algorithms\TSP\Genetic;
use Representations\TSP\Graph;

require_once 'vendor/autoload.php';

$graph = new Graph();
$graph->numberOfVertices = 10;
$graph->weights =
    [
        [3, 5, 4, 72, 36, 25, 37, 120, 2, 15],
        [31, 5, 34, 71, 3, 2, 7, 10, 12, 15],
        [3, 25, 46, 87, 3, 2, 7, 10, 2, 1],
        [32, 15, 47, 67, 3, 2, 7, 10, 12, 15],
        [33, 25, 49, 7, 3, 2, 7, 10, 2, 1],
        [31, 58, 47, 57, 3, 2, 7, 10, 12, 5],
        [33, 35, 41, 47, 3, 2, 7, 1, 12, 15],
        [35, 54, 42, 37, 3, 2, 7, 10, 12, 1],
        [73, 25, 43, 27, 3, 2, 7, 1, 12, 15],
        [35, 53, 44, 17, 3, 2, 7, 10, 12, 15],
    ];
$algorithm = new Genetic();

$algorithm->run();
