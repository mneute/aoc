#!/usr/bin/env php
<?php declare(strict_types=1);

require './vendor/autoload.php';

use App\Command\RunPuzzle;
use Symfony\Component\Console\Application;

$command = new RunPuzzle();

$application = new Application('advent-of-code', '1.0.0');
$application->add($command);

$application
        ->setDefaultCommand($command->getName(), true)
        ->run();
