#!/usr/bin/env php
<?php declare(strict_types=1);

require './vendor/autoload.php';

use App\AbstractPuzzle;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\SingleCommandApplication;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Stopwatch\Stopwatch;

new SingleCommandApplication()
        ->setCode(function (InputInterface $input, OutputInterface $output, #[Argument] string $year, #[Argument] string $day): int {
            $day = str_pad($day, 2, '0', STR_PAD_LEFT);

            $className = sprintf('App\\Year%1$s\\Day%2$s\\Day%2$s', $year, $day);

            if (!class_exists($className)) {
                throw new InvalidArgumentException(sprintf('Class %s does not exist', $className));
            }

            $instance = new $className();
            if (!$instance instanceof AbstractPuzzle) {
                throw new LogicException(sprintf('%s should be an instance of %s', $className, AbstractPuzzle::class));
            }

            $event = new Stopwatch(true)->start('puzzle');

            $result = $instance->run();

            $event->stop();

            $io = new SymfonyStyle($input, $output);
            $io->definitionList(
                    ['Part 1' => $result->part1],
                    ['Part 2' => $result->part2],
            );

            $io->writeln(sprintf(' %d ms - %.2F MiB', $event->getDuration(), $event->getMemory() >> 20));

            return 0;
        })
        ->run();
