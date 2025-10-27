<?php

declare(strict_types=1);

namespace CommandBuilder;

use Castor\CommandBuilder\CommandBuilderInterface;

final class DockerCompose implements CommandBuilderInterface
{
    private string $container = 'php';
    private bool $build = false;
    private bool $bash = false;
    private array $puzzle = [];

    public function container(string $name): self
    {
        $this->container = $name;
        return $this;
    }

    public function build(): self
    {
        $this->build = true;

        $this->bash = false;
        $this->puzzle = [];

        return $this;
    }

    public function bash(): self
    {
        $this->bash = true;

        $this->build = false;
        $this->puzzle = [];

        return $this;
    }

    public function puzzle(int $year, int $day, bool $test): self
    {
        $this->puzzle = [$year, $day];
        if ($test) $this->puzzle[] = '--test';

        $this->build = $this->bash = false;

        return $this;
    }

    #[\Override]
    public function getCommand(): array
    {
        $command = [
            'docker',
            'compose',
            '-f',
            'docker-compose.yml',
        ];

        if ($this->build) {
            return [
                ...$command,
                'build',
                '--pull',
                $this->container,
            ];
        } elseif ($this->bash) {
            return [
                ...$command,
                'run',
                '--rm',
                $this->container,
                '/bin/bash',
            ];
        } elseif ([] !== $this->puzzle) {
            return [
                ...$command,
                'run',
                '--rm',
                $this->container,
                'php',
                'app.php',
                ...$this->puzzle,
            ];
        }

        throw new \RuntimeException('You need to call one of the run() or build() methods');
    }
}
