include laravel-app/.env.example

install:
	@make cp
	@make down
	@make build
	@make up 
	@make composer-update
	@make perm-storage
	@make data
	@make cron-start
	@make msg
	
#Passos de instalação...	
cp:
	cd laravel-app/ && rm .env -f && cp .env.example .env && cd ..
down:
	docker-compose down --remove-orphans
build:
	docker-compose build --no-cache --force-rm
stop:
	docker-compose stop
up:
	docker-compose up -d
composer-update:
	docker exec coderockr-app bash -c "composer update && php artisan key:generate"	
perm-storage:
	docker exec -t coderockr-app bash -c 'chown -R www-data:www-data /var/www/html/storage'	
data:
	docker exec coderockr-app bash -c "php artisan migrate"
	docker exec coderockr-app bash -c "php artisan db:seed"
cron-start:
	docker exec -t coderockr-app bash -c "/etc/init.d/cron start"

#Lista de comandos...
in:
	docker exec -it coderockr-app bash
predis:
	docker exec -it predis bash
purge:
	docker-compose down --rmi all
queue:
	docker exec -t coderockr-app bash -c "php artisan queue:work"
test:
	docker exec -t coderockr-app bash -c "php artisan test"
schedule-run:
	docker exec -t coderockr-app bash -c "php artisan schedule:run"
cron-log:
	docker exec -t coderockr-app bash -c "tail -f /var/log/cron.log"
cron-stop:
	docker exec -t coderockr-app bash -c "/etc/init.d/cron stop"
msg:
	@echo "########################################################################"
	@echo "### Clique aqui para ver a api funcionando ${APP_URL}/api ###"
	@echo "########################################################################"


