<?php

declare(strict_types=1);

namespace CommandBuilder;

use Castor\CommandBuilder\CommandBuilderInterface;

final class DockerCompose implements CommandBuilderInterface
{
    private string $container = 'php';
    private bool $build = false;
    private bool $bash = false;

    /**
     * @var string[]
     */
    private array $puzzle = [];
    private array $command = [];

    public function container(string $name): self
    {
        $this->container = $name;

        return $this;
    }

    public function build(): self
    {
        $this->reset();
        $this->build = true;

        return $this;
    }

    public function bash(): self
    {
        $this->reset();
        $this->bash = true;

        return $this;
    }

    public function puzzle(int $year, int $day, bool $test): self
    {
        $this->reset();
        $this->puzzle = [$year, $day];
        if ($test) $this->puzzle[] = '--test';

        return $this;
    }

    public function command(array $command): self
    {
        $this->reset();
        $this->command = $command;

        return $this;
    }

    #[\Override]
    public function getCommand(): array
    {
        $command = [
            'docker',
            'compose',
            '--file=docker-compose.yml',
        ];

        if ($this->build) {
            return [
                ...$command,
                'build',
                '--pull',
                $this->container,
            ];
        }
        $runCommand = [
            ...$command,
            'run',
            '--rm',
            $this->container,
        ];

        if ($this->bash) {
            return [
                ...$runCommand,
                '/bin/bash',
            ];
        } elseif ([] !== $this->puzzle) {
            return [
                ...$runCommand,
                'php',
                'app.php',
                ...$this->puzzle,
            ];
        } elseif ([] !== $this->command) {
            return [
                ...$runCommand,
                ...$this->command,
            ];
        }

        throw new \RuntimeException('You need to call one of the run(), build(), puzzle() or command() methods');
    }

    private function reset(): void
    {
        $this->build = $this->bash = false;
        $this->puzzle = $this->command = [];
    }
}
