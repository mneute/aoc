<?php

use Castor\Attribute\AsContext;
use Castor\Attribute\AsTask;
use Castor\Context;
use CommandBuilder\DockerCompose;
use function Castor\import;
use function Castor\run;

import(__DIR__);

#[AsTask(description: 'Build the docker image')]
function build(): void
{
    run((new DockerCompose())->build());
}

#[AsTask(description: 'Starts a bash session inside the container', default: true)]
function bash(): void
{
    run((new DockerCompose())->run());
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
