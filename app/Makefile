all: build

build:
	echo 'TODO'

exec-php:
	docker exec -it social_photos_php bash

exec-mysql:
	docker exec -it social_photos_mysql bash

db-validate:
	php bin/console doctrine:schema:validate

db-update:
	php bin/console doctrine:schema:update --dump-sql --force

project-cache-clear:
	php bin/console cache:clear --env=prod
	php bin/console cache:clear --env=dev
	# php bin/console cache:pool:clear
	# php bin/console doctrine:cache:clear-collection-region
	# php bin/console doctrine:cache:clear-entity-region
	php bin/console doctrine:cache:clear-metadata
	php bin/console doctrine:cache:clear-query
	# php bin/console doctrine:cache:clear-query-region
	php bin/console doctrine:cache:clear-result

.PHONY: build exec-php exec-mysql db-update db-validate project-cache-clear