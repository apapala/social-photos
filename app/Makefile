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

.PHONY: exec-php exec-mysql