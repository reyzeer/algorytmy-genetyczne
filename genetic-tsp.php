#!/usr/bin/php
<?php

require_once 'vendor/autoload.php';

$algorithm = new \Algorithms\TSP\Genetic();
$algorithm->run();
