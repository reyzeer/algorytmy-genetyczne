#!/usr/bin/php
<?php

use Algorithms\GreedyAlgorithm;

require_once 'vendor/autoload.php';

$algorithm = new GreedyAlgorithm();
$algorithm->run();
$algorithm->result();
