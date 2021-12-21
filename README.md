# Coderockr

Sistema desenvolvido como teste para vaga de Desenvolvedor Backend na Coderockr

### Tecnologias Utilizadas
- Framework Backend: Laravel 8
- Banco de Dados Relacional: MySQL

### Bibliotecas Utilizadas
- L5 Swagger (darkaonline/l5-swagger)
  - Utilizado para gerar a documentação da API do projeto

## Instruções para Instalação

Link do Repositório
```
https://github.com/marcellowb/backend-test

```


- Após baixar o o projeto do repositório, executar os comandos
```
composer install

```

- Configurar as credenciais do banco de dados (mysql) no arquivo .env
- Executar o comando para configuração da chave do Laravel

```
php artisan key:generate

```

- Executar o comando para criação do banco de dados (migrations seeds)

```
php artisan migrate

```

- Executar o projeto

```
Servidor Web: php artisan serve
```

- Documentação da API

Após rodar o projeto, acessar através do browser o link

```
http://{HOST}/api/documentation

Ex: http://localhost:8000/api/documentation
```
