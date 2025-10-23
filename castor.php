<?php

use Castor\Attribute\AsContext;
use Castor\Attribute\AsTask;
use Castor\Context;
use function Castor\run;

const DOCKER_COMPOSE = 'docker compose -f docker-compose.yml ';

#[AsTask(description: 'Build the docker image')]
function build(): void
{
    run(DOCKER_COMPOSE . 'build --pull php');
}

#[AsTask(description: 'Starts a bash session inside the container', default: true)]
function bash(): void
{
    run(DOCKER_COMPOSE . 'run --rm php /bin/bash');
}

#[AsContext(default: true)]
function defaultContext(): Context
{
    return new Context(tty: true);
}
