up:
	docker-compose up -d
	docker-compose exec php ./bin/console doctrine:migrations:migrate -n

down:
	docker-compose down -v

rebuild:
	docker-compose down -v --remove-orphans
	docker-compose rm -vsf
	docker-compose up -d --build
