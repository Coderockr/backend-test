init:
	cp .env.example
build:
	docker-compose build --no-cache
	docker-compose up -d
	docker-compose logs -f
rebuild:
	docker-compose down --remove-orphans --volumes
	sudo rm -rf data
	make build
run: 
	docker-compose down
	docker-compose up -d
	docker-compose logs -f
down: 
	docker-compose down --remove-orphans --volumes
	
terminal:
	docker exec -it servi_app bash