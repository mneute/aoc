DOCKER_COMPOSE = docker compose -f docker-compose.yml

build: # Build the container
	${DOCKER_COMPOSE} build --pull php

bash: # Starts a shell inside your container
	${DOCKER_COMPOSE} run --rm php /bin/bash
