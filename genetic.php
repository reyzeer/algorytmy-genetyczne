#!/usr/bin/php
<?php

use Algorithms\Func\Genetic;

require_once 'vendor/autoload.php';

$algorithm = new Genetic;
$algorithm->iterations = 5000;
$algorithm->run();
$algorithm->result();
echo 'Time: ' . $algorithm->getTime() . "\n";
