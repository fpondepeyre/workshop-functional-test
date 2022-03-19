SHELL := /bin/bash

install:
	symfony console doctrine:database:drop --force --env=dev || true
	symfony console doctrine:database:create --env=dev
	symfony console doctrine:migrations:migrate -n --env=dev
	symfony console doctrine:fixtures:load -n --env=dev
.PHONY: install

tests:
	symfony console doctrine:database:drop --force --env=test || true
	symfony console doctrine:database:create --env=test
	symfony console doctrine:migrations:migrate -n --env=test
	symfony console doctrine:fixtures:load -n --env=test
	XDEBUG_MODE=coverage symfony php ./vendor/bin/simple-phpunit $@ --coverage-html build
.PHONY: tests