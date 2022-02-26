# Investment Manager

Backend do projeto Investment Manager, utiliza NestJS em sua composição.

## Rodando aplicação local

### Instalação:

Este projeto utiliza docker como dependência obrigatória.

1. Renomeie o arquivo .env.example para .env
2. Execute: `docker-compose up`

A aplicação será inicializada em [http://localhost:5000](http://localhost:5000) no ambiente local.

### Migrations:

Para criar as tabelas do banco de dados do projeto:

1. Acesse o container: `docker exec -it id_do_container bash`
2. Execute `yarn migrate`

## Testes unitários

Para executar os testes unitários:

1. Acesse o container: `docker exec -it id_do_container bash`
2. Execute `yarn test`

### Tecnologias

- Node v14
- NestJs
- MySQl

## Dependências

- Typeorm
- Mysql
- Jest
- TypeOrm
- Helmet
- Moment
- Lodash

### Documentação

A API foi documentava via Swagger, e sua visualização esta disponível em [http://localhost:5000/api](http://localhost:5000/api) no ambiente local.
