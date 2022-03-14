### Passo a passo

Criar o Arquivo .env
```sh

cp .env.example .env
```

Atualizar as variáveis de ambiente do arquivo .env
```dosini
APP_NAME=Investiment
APP_URL=http://localhost:8280

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=investiment
DB_USERNAME=root
DB_PASSWORD=root

CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis

REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379
```

Subir os containers do projeto
```sh
docker-compose up -d
```

Acessar o container
```sh
docker-compose exec investiment bash
```

Instalar as dependências do projeto
```sh
composer install
```

Gerar a key do projeto Laravel
```sh
php artisan key:generate
```
Acessar o PhpmyAdmin em [http://localhost:8081](http://localhost:8081) informando o mesmo usuário(root) e senha(root) utilizado no arquivo .env.

Criar e preencher as tabelas no banco de dados
```sh
php artisan migrate --seed
```

Rodar os arquivos de teste
```sh
php artisan test
```

### Documentação da Api
link: http://localhost:8280/docs

### Tecnologias Utilizadas
O projeto foi construído com o framework Laravel 8. Escolhi esta tecnologia por já estar familiarizado com a mesma e por ela ser muito efetiva na construção de uma api. 
