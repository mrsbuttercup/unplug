.SILENT:
.PHONY: build deps composer composer-install composer-update reload test run-tests doco start stop destroy build-images develop production rebuild

current-dir := ${CURDIR}

# Main targets
build: deps start
deps: composer-install

# Composer
composer-install: CMD=install
composer-update: CMD=update

# Usage example (add a new dependency): `make composer CMD="require --dev symfony/var-dumper ^4.2"`
composer composer-install composer-update:
	@docker run --rm --interactive --user $(id -u):$(id -g) \
		--volume $(current-dir):/app \
		--volume ${COMPOSER_HOME:-$HOME/.composer}:/tmp \
		composer $(CMD) \
			--ignore-platform-reqs \
			--no-ansi \
			--no-interaction

# Clear cache
# OpCache: Restarts the unique process running in the PHP FPM container
# Nginx: Reloads the server
reload:
	@docker-compose exec php kill -USR2 1
	@docker-compose exec app nginx -s reload

# Tests
test:
	@docker-compose exec -T php make run-tests

run-tests:
	mkdir -p build/test_results/phpunit
	./vendor/bin/phpunit --exclude-group='disabled' --log-junit build/test_results/phpunit/junit.xml tests

# Docker Compose
start: CMD=up --detach --remove-orphans
stop: CMD=stop
destroy: CMD=down
build-images: CMD=build --pull --force-rm --no-cache

# Usage: `make doco CMD="ps --services"`
# Usage: `make doco CMD="build --parallel --pull --force-rm --no-cache"`
doco start stop destroy build-images:
	@docker-compose $(CMD)

rebuild:
	make build-images
	make start


# Environments
develop:
	@docker-compose --file docker-compose.yml up --detach --remove-orphans

production:
	make deps
	@docker-compose --file docker-compose.yml --file docker-compose.prod.yml up --detach --remove-orphans
	@docker-compose exec php bin/console secrets:decrypt-to-local --force --env=prod
