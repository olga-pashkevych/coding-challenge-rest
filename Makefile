.PHONY: tests

tests:
	docker run -v `pwd`:/app -w /app --rm keinos/php8-jit vendor/bin/phpunit -c phpunit.xml.dist

start:
	docker-compose up -d --build

stop:
	docker-compose down

composer-install:
	docker run --rm --interactive --tty -v `pwd`:/app composer install

migrate:
	docker exec -it test_app bin/console doctrine:migrations:migrate

fixture:
	docker exec -it test_app bin/console doctrine:fixtures:load