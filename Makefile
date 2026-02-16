DOCKER_COMPOSE=docker compose -f docker/docker-compose.yml --env-file docker/.env

.PHONY: up down restart ps

up:
	${DOCKER_COMPOSE} up -d

down:
	${DOCKER_COMPOSE} down

restart:
	${DOCKER_COMPOSE} down
	${DOCKER_COMPOSE} up -d

ps:
	${DOCKER_COMPOSE} ps
