#!/usr/bin/php
<?php

use Algorithms\Func\GradientDescent;

require_once 'vendor/autoload.php';

$algorithm = new GradientDescent();
$algorithm->run();
$algorithm->result();
echo 'Time: ' . $algorithm->getTime() . "\n";
