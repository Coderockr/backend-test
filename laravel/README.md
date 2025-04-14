<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

## Documentação API em Laravel

Esta aplicação é responsável por realizar o controle de um investmento, sendo possível criar um investmento, sacar e visualizar.

Bibliotecas e versões utilizadas nesse sistema:

- "php": ^8.0.2,
- "laravel/framework": 9.19


Abaixo segue o passo a passo para executar o projeto e realizar os testes

### Passo a Passo

- Realizar o download do repositório
- Abrir o CMD no diretório root do projeto e executar "composer update"
- Executar comando para criar o .env e "application key" no .env "cp .env.example .env" e em seguida "php artisan key:generate"
- Atualizar as informações do arquivo .env com a sua conexão de banco de dados
- Executar os migrations para o sistema criar a base de dados "php artisan migrate"
- Iniciar o ambiente de desenvolvimento do laravel "php artisan serve"

#### Rotas e Funcionalidades

- Documentação das Rotas:
    - https://documenter.getpostman.com/view/9534004/2s8YsqUuEy
