.DEFAULT_GOAL := help
SHELL = /bin/bash

.PHONY: default composer migrate fixtures-load
default: help

docker-start:
	@echo "${BLUE}Starting all containers:${NC}"
	@docker-compose up -d
	@docker-compose build

docker-stop:
	@echo "${BLUE}Stopping all containers:${NC}"
	@docker-compose down -v

docker-doctrine-migration-migrate:
	@docker exec -ti blog_site sh -c "cd blog && php bin/console d:m:m --no-interaction"

docker-messenger:
	@docker exec -ti blog_site sh -c "cd blog && php bin/console messenger:consume amqp"

docker-doctrine-fixtures-load:
	@docker exec -ti blog_site sh -c "cd blog && php bin/console doctrine:fixtures:load --purge-with-truncate --no-interaction"

docker-phpunit-tests:
	@mkdir -p backend/sf-demo/reports/
	@docker exec -ti blog_site sh -c "cd blog && php bin/simple-phpunit --group=unitTest --coverage-html reports/""

docker-c-update:
	@echo "${BLUE}Updating your application dependencies:${NC}"
	@docker exec -ti blog_site sh -c "cd blog && composer update"

docker-c-install:
	@echo "${BLUE}Installing your application dependencies:${NC}"
	@docker exec -ti blog_site sh -c "cd blog && composer install"

docker-clean:
	@echo "${BLUE}Clean directories:${NC}"
	@rm -Rf blog/vendor
	@rm -Rf blog/var/cache
	@rm -Rf blog/var/log
	@rm -Rf blog/.env.local
