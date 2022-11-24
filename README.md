# Back End Test Project

Este projeto é construido em symfony.

## Bibliotecas instaladas

- [SymfonyMakerBundle](https://symfony.com/bundles/SymfonyMakerBundle/current/index.html)
- [DoctrineBundle](https://symfony.com/bundles/DoctrineBundle/current/installation.html)
- [SymfonySecurityBundle](https://symfony.com/doc/current/security.html)
- [SymfonyUid](https://symfony.com/doc/current/components/uid.html#installation)
- [LexikJWTAuthenticationBundle](https://symfony.com/bundles/LexikJWTAuthenticationBundle/current/index.html)
- [PhpUnit](https://symfony.com/doc/current/testing.html#the-phpunit-testing-framework)
- [SymfonyValidation](https://symfony.com/doc/current/validation.html#installation)

## Instalação do Projeto

### Dependencias

- PHP >- 8.1
- composer
- docker
- docker-compose

### Guia de instalação

para instalação em ambiente local, faça o clone do projeto e use o composer para instalar as dependencias:

´´´
composer install
´´´

Use o docker compose para instalar o banco de dados, eu utilizo mysql mas pode ficar a sua escolha, siga a documentação para instalar outros bancos de dados [documentação](https://symfony.com/doc/current/doctrine.html#configuring-the-database).

´´´
docker-compose up -d db
´´´

Se tiver o [symfony-cli](https://symfony.com/download) use o seguinte comando:

´´´
symfony server:start
´´´

Caso não tenha symfony-cli use o PHP puro:

´´´
php -S localhost:8000 -t public
´´´
