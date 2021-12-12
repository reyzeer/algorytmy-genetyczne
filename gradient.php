#!/usr/bin/php
<?php

use Algorithms\Gradient;

require_once 'vendor/autoload.php';

$algorithm = new Gradient();
$algorithm->run();
$algorithm->result();
