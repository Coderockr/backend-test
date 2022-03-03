# API para gerenciar investimentos de proprietários.

## Requisitos:

- PHP ^7.3
- MySQL ~8.0.27
- Lumen Framework ^8.3.1

## Instalação e configuração

1. Após o pull, instale as dependências rodando no terminal: 
    ```composer install```
    
2. Crie um arquivo ```.env``` na raíz do projeto, siga o arquivo ```.env.example```.

3. A chave da aplicação precisa ser gerada, para isso, execute no terminal: ```php artisan key:generate```

4. Gere também o segredo de token, utilizado pela biblioteca responsável pela autenticação de proprietários:
    ```php artisan jwt:secret```
    
Ao finalizar estas quatro etapas, seu arquivo **.env** deve se parecer com isto:
```
APP_NAME="Backend Test"
APP_ENV=local
APP_KEY={YOUR_KEY_GENERATED_ON_STEP_3}
APP_DEBUG=true
APP_URL=http://localhost
APP_TIMEZONE="America/Sao_Paulo"

LOG_CHANNEL=stack
LOG_SLACK_WEBHOOK_URL=

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE={YOUR_DB}
DB_USERNAME={YOUR_USER}
DB_PASSWORD={YOUR_PASS}

CACHE_DRIVER=file
QUEUE_CONNECTION=sync

JWT_SECRET={YOUR_TOKEN_GENERATED_ON_STEP_4}
```

5. Esta API não possui uma interface HTTP para criar e gerenciar diretamente os usuários(propriétarios), portanto tenha seu banco de dados populado com os mesmos... Para fazer isso basta migrar o banco com o parametro *--seed*:
```php artisan migrate:fresh --seed```

Se as informações de seu banco de dados estarem corretas, tudo deve ocorrer bem e você ja está pronto para consumir a API.

[Consulte aqui](https://github.com/luciano-eber/backend-test/wiki) a documentação.

## Execução

No terminal, execute: `php artisan serve` ou o servidor embutido do php `php -S localhost:8080 -t public` se preferir, para rodar o server.

## Testando 

Para executar os testes basta rodar: `./vendor/bin/phpunit` na raíz.
Se você deseja rodar os testes em um banco de dados diferente, sugiro utilizar um arquivo `.env.testing`, com os valores do banco de teste.

## Por que o Lumen?

Porque o lumen pode oferecer um projeto simples e uma API consistente, tanto se comunicando com o banco de dados, quanto no retorno de respostas http seguindo diversos padrões, graças ao [Eloquent ORM](https://laravel.com/docs/8.x/eloquent) e ao laravel com seu sistema de injeção de dependência poderoso mas sem todo aquele peso extra de features desnecessárias para uma simples API. 

## Biblioteca de autenticação de proprietários

Os recursos de investimentos da API são autenticados pelo proprietário dos mesmos, para isso escolhi a autenticação via JWT e a biblioteca https://github.com/tymondesigns/jwt-auth, acredito ser de confiança e é muito fácil de acoplar ou desacoplar da aplicação.

## Lumen Framework Docs:

[Lumen website](https://lumen.laravel.com/docs).