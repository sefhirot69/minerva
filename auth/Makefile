# VARIABLES
DOCKER_COMPOSE = docker compose
CONTAINER      = minerva-auth
EXEC           = docker exec -t --user=root $(CONTAINER)
EXEC_PHP       = $(EXEC) php
SYMFONY        = $(EXEC_PHP) bin/console
COMPOSER       = $(EXEC) composer
CURRENT-DIR  := $(dir $(abspath $(lastword $(MAKEFILE_LIST))))
CURRENT_UID  := $(shell id -u)

.DEFAULT_GOAL := deploy

deploy: build
	@echo "📦 Build done"

build: create_env_file rebuild

deps: composer-install
	make create/database
	make doctrine/migrate

update-deps: composer-update

generate-key:
	@sh .docker/genrsa.sh

create_env_file:
	@if [ ! -f .env.local ]; then cp .env .env.local; fi
# 🐘 Composer
composer-install ci: ACTION=install

composer-update cu: ACTION=update $(module)

composer-require cr: ACTION=require $(module)

composer composer-install ci composer-update composer-require cr: create_env_file
	$(COMPOSER) $(ACTION) \
			--ignore-platform-reqs \
			--no-ansi
# 🐳 Docker Compose
start: create_env_file
	@echo "🚀 Deploy!!!"
	@$(DOCKER_COMPOSE) up -d
	make deps
	make create/database
	make doctrine/migrate
stop:
	$(DOCKER_COMPOSE) stop
down:
	$(DOCKER_COMPOSE) down
recreate:
	@echo "🔥 Recreate container!!!"
	$(DOCKER_COMPOSE) up -d --build --remove-orphans --force-recreate
	make deps
rebuild:
	@echo "🔥 Rebuild container!!!"
	$(DOCKER_COMPOSE) build --pull --force-rm --no-cache
	make start

test:
	docker exec -t $(CONTAINER) ./vendor/bin/phpunit -v

# 🦝 Apache
reload:
	$(EXEC) /bin/bash service apache2 restart || true

#clear cache
clear:
	$(SYMFONY) cache:clear

bash:
	$(DOCKER_COMPOSE) exec -it $(CONTAINER) /bin/bash

# 📦 Database
create/database:
	$(SYMFONY) doctrine:database:create --if-not-exists --env=dev
	$(SYMFONY) doctrine:database:create --if-not-exists --env=test
	@echo "🎊 Database created!"

#doctrine:migrations:migrate
d-m-m:
doctrine/migrate:
	$(SYMFONY) doctrine:migrations:migrate --no-interaction --env=dev
	$(SYMFONY) doctrine:migrations:migrate --no-interaction --env=test
#make:migration
m-m:
	$(SYMFONY) make:migration

#fixtures
fixtures:
	$(SYMFONY) doctrine:fixtures:load --no-interaction

#Linter
cs:
	$(EXEC_PHP) ./vendor/bin/php-cs-fixer fix --diff
	@echo "Coding Standar Fixer Executed ✅"

cs-prev:
	$(EXEC_PHP) ./vendor/bin/php-cs-fixer fix --dry-run --diff
	@echo "Coding Standar Fixer Executed ✅"