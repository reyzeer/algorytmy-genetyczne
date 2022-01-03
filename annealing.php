#!/usr/bin/php
<?php

use Algorithms\Annealing;

require_once 'vendor/autoload.php';

$algorithm = new Annealing;
$algorithm->run();
$algorithm->result();
