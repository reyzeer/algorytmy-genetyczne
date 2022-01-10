#!/usr/bin/php
<?php

use Algorithms\Func\Annealing;

require_once 'vendor/autoload.php';

$algorithm = new Annealing;
$algorithm->run();
$algorithm->result();
