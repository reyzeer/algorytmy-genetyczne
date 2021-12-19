#!/usr/bin/php
<?php

use Algorithms\GradientDescent;

require_once 'vendor/autoload.php';

$algorithm = new GradientDescent();
$algorithm->run();
$algorithm->result();
