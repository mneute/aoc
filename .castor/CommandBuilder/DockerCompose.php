<?php

declare(strict_types=1);

namespace CommandBuilder;

use Castor\CommandBuilder\CommandBuilderInterface;

final class DockerCompose implements CommandBuilderInterface
{
    private string $container = 'php';
    private bool $build = false;
    private bool $run = false;

    public function container(string $name): self
    {
        $this->container = $name;
        return $this;
    }

    public function build(): self
    {
        $this->build = true;
        $this->run = false;
        return $this;
    }

    public function run(): self
    {
        $this->build = false;
        $this->run = true;
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
        } elseif ($this->run) {
            return [
                ...$command,
                'run',
                '--rm',
                $this->container,
                '/bin/bash',
            ];
        }

        throw new \RuntimeException('You need to call one of the run() or build() methods');
    }
}
