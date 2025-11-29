<?php

declare(strict_types=1);

use Castor\Attribute\AsContext;
use Castor\Attribute\AsOption;
use Castor\Attribute\AsTask;
use Castor\Context;
use CommandBuilder\DockerCompose;
use Symfony\Component\Console\Input\InputOption;

use function Castor\import;
use function Castor\run;

import(__DIR__);

#[AsTask(description: 'Build the docker image')]
function build(): void
{
    run(new DockerCompose()->build());
}

#[AsTask(description: 'Starts a bash session inside the container', default: true)]
function bash(): void
{
    run(new DockerCompose()->bash());
}

#[AsTask(description: 'Runs a specific puzzle by year and day', aliases: ['run'])]
function runPuzzle(
    int $year,
    int $day,
    #[AsOption(shortcut: 't', mode: InputOption::VALUE_NONE)]
    bool $test,
): void {
    run(new DockerCompose()->puzzle($year, $day, $test));
}

#[AsTask(description: 'Runs quality assurance commands')]
function runQa(): void
{
    rector(false);
    phpCsFixer(false);
}

#[AsTask(description: 'Runs Rector')]
function rector(
    #[AsOption(mode: InputOption::VALUE_NONE)]
    bool $dryRun,
): void {
    $command = [
        'vendor/bin/rector',
        'process',
        '--config',
        'rector.php',
    ];
    if ($dryRun) $command[] = '--dry-run';

    run(new DockerCompose()->command($command));
}

#[AsTask(description: 'Runs PhpCsFixer')]
function phpCsFixer(
    #[AsOption(mode: InputOption::VALUE_NONE)]
    bool $dryRun,
): void {
    $command = [
        'vendor/bin/php-cs-fixer',
        'fix',
        '--config',
        'php-cs-fixer.php',
        '--diff',
        '--verbose',
    ];
    if ($dryRun) $command[] = '--dry-run';

    run(new DockerCompose()->command($command));
}

#[AsTask(description: 'Updates the permissions of the project dir', aliases: ['chown'])]
function updatePermissions(): void
{
    run('sudo chown -R ${USER}: .');
}

#[AsContext(default: true)]
function defaultContext(): Context
{
    return new Context(tty: true);
}
