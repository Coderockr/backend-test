# Coderockr Interview Challenge

Essa é a solução que eu ([@scrlkx](https://github.com/scrlkx)) construí durante o processo seletivo de uma vaga
na [@Coderockr](https://github.com/Coderockr). Esta é a base de uma API para uma rede social de eventos.

## Stack

A API foi construída com a stack PHP 8 + Laravel 8, pensando em rodar sobre uma base de dados MySQL. A autenticação é
uma responsabilidade do [Laravel Passport](https://laravel.com/docs/8.x/passport) e os testes são escritos
com [Pest PHP](https://pestphp.com). As filas de processamento são gerenciadas com [Redis](https://redis.io) e ainda
temos o [MailHog](https://github.com/mailhog/MailHog) para preview de e-mails. Para ambientes de desenvolvimento
utiliza-se o [Laravel Sail](https://laravel.com/docs/8.x/sail) que roda sobre [Docker](https://www.docker.com) e também
querer [docker-compose](https://docs.docker.com/compose).

## Setup

1. Clone o repositório
2. Crie uma cópia de `.env` com `cp .env.example .env`
3. Instale as dependências com `composer install` (isso requer o [Composer](https://getcomposer.org))
4. Construa a estrutura com `sail up -d`
5. Acesse o container principal com `sail shell`
6. Crie a sua chave com `php artisan key:generate`
7. Rode migrations e seeds com `php artisan migrate:refresh --seed`
8. Gere suas chaves Passport com `php artisan passport:install`
9. Me avise se algo sair errado

A partir deste momento, os seguintes recursos estão disponíveis:

- A API na porta 80
- Um banco de dados MySQL na porta 3306
- Uma instância de Redis na porta 6379
- O painel do MailHog na porta 1025

## Documentação

A documentação da API está disponível publicamente [neste link](https://documenter.getpostman.com/view/5768628/TWDTKxnK)
e se você estiver pensando em utilizar Postman para simular requisições pode importar uma coleção completa utilizando
este link: https://www.getpostman.com/collections/b954894b6831b44c5a2c

## Testes

Para executar os testes disponíveis:

1. Acesse o container principal com `sail shell`
2. Execute `composer test`

## Envios de e-mail

A solução entregue não considera fazer envios reais de e-mail, apesar de que você pode fazer isso apenas mudando algumas
configurações locais. Ao invés disso ela foi pensada para simular envios que são posteriormente capturados pelo MailHog.

O painel do MailHog está disponível em [http://localhost:8025](http://localhost:8025).
