up:
	docker-compose up -d

down:
	docker-compose down -v

rebuild:
	docker-compose down -v --remove-orphans
	docker-compose rm -vsf
	docker-compose up -d --build
