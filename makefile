.DEFAULT_GOAL := help
SHELL = /bin/bash

.PHONY: default composer migrate fixtures-load
default: help

docker-start:
	@echo "${BLUE}Starting all containers:${NC}"
	@docker-compose up -d

docker-stop:
	@echo "${BLUE}Stopping all containers:${NC}"
	@docker-compose down -v

docker-doctrine-migration-migrate:
	@docker-compose exec -T php bin/console d:m:m --no-interaction

docker-doctrine-fixtures-load:
	@docker-compose exec -T php bin/console doctrine:fixtures:load --purge-with-truncate --no-interaction

docker-phpunit-tests:
	@mkdir -p backend/sf-demo/reports/
	@docker-compose exec -T php bin/simple-phpunit --group=unitTest --coverage-html reports/

docker-c-update:
	@echo "${BLUE}Updating your application dependencies:${NC}"
	@docker-compose exec -T php composer update

docker-c-install:
	@echo "${BLUE}Installing your application dependencies:${NC}"
	@docker-compose exec -T php composer install

