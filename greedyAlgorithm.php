#!/usr/bin/php
<?php

use Algorithms\Func\GreedyAlgorithmBinary;

require_once 'vendor/autoload.php';

$algorithm = new GreedyAlgorithmBinary();
$algorithm->run();
$algorithm->result();
echo 'Time: ' . $algorithm->getTime() . "\n";
