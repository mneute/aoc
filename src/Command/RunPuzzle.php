<?php

declare(strict_types=1);

namespace App\Command;

use App\AbstractPuzzle;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Stopwatch\Stopwatch;

final class RunPuzzle extends Command
{
    public function __construct()
    {
        parent::__construct('run:puzzle');
    }

    protected function configure(): void
    {
        $this
            ->addArgument('year', InputArgument::REQUIRED)
            ->addArgument('day', InputArgument::REQUIRED)
            ->addOption('test', 't');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $year = $input->getArgument('year');
        $day = str_pad($input->getArgument('day'), 2, '0', STR_PAD_LEFT);
        $test = $input->getOption('test');

        $className = sprintf('App\\Year%1$s\\Day%2$s\\Day%2$s', $year, $day);
        if (!class_exists($className)) throw new \InvalidArgumentException(sprintf('Class %s does not exist', $className));
        if (!is_subclass_of($className, AbstractPuzzle::class)) throw new \LogicException(sprintf('%s should be an instance of %s', $className, AbstractPuzzle::class));

        $io = new SymfonyStyle($input, $output);

        $instance = new $className($test);

        if (!file_exists($instance->getFilePath())) {
            $io->writeln('Missing input file, trying to download it ...');
            if ($test) throw new \RuntimeException(sprintf('There is no way to automatically retrieve the test input. Get it from the website and store it in %s', $instance->getFilePath()));

            $this->downloadInputFile($instance->getFilePath(), (int) $year, (int) $day);
            $io->writeln('Done ! Resuming ...');
        }

        $event = new Stopwatch(true)->start('puzzle');

        $result = $instance->run();

        $event->stop();

        $io->definitionList(
            ['Part 1' => $result->part1],
            ['Part 2' => $result->part2],
        );

        $io->writeln(sprintf(' %d ms - %.2F MiB', $event->getDuration(), $event->getMemory() >> 20));

        return 0;
    }

    private function downloadInputFile(string $getFilePath, int $year, int $day): void
    {
        $cookie = $_ENV['AOC_SESSION_ID']
            ?: throw new \RuntimeException('Missing session ID, set the AOC_SESSION_ID value in your .env.local file');

        $client = HttpClient::create([
            'headers' => ['Cookie' => sprintf('session=%s', $cookie)]
        ]);

        $response = $client->request('GET', sprintf('https://adventofcode.com/%d/day/%d/input', $year, $day));

        file_put_contents($getFilePath, $response->getContent());
    }
}
