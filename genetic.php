#!/usr/bin/php
<?php

use Algorithms\Genetic;

require_once 'vendor/autoload.php';

$algorithm = new Genetic;
$algorithm->iterations = 5000;
$algorithm->run();
$algorithm->result();
