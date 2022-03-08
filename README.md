# Back End Test Project <img src="https://coderockr.com/assets/images/coderockr.svg" align="right" height="50px" />

API desenvolvida para o test de backend da CodeRockr.

## Visão Geral

Este projeto foi desenvolvido como parte do processo seletivo da CodeRockr. Trata-se de uma API que armazena e gerencia investimentos.
A aplicação foi desenvolvida com PHP e sem o uso de frameworks, por escolhas próprias, uma vez que a especificação do teste oferece essa liberdade. Essa decisão foi tomada com o objetivo de demonstrar domínio de conceitos e técnicas importantes da linguagem, independente de frameworks. Os únicos recursos externos utilizados no desenvolvimento foram o autoloader do composer e o PHPUnit, para os testes automatizados.
Alguns dos conceitos e técnicas implentadas no projeto, ainda que de forma simplificada, são:
- Uso de variáveis de ambiente;
- MVC;
- Autenticação
- Gerenciamento de rotas;
- Gerenciamento de conexões no banco de dados;
- Gerenciamento de transações no banco de dados;
- Padrão de projeto Repository;

### Features
- [x] Criar investimentos;
- [x] Atualização de saldo;
- [x] Visualizar investimentos (com paginação);
- [x] Realizar a retirada de um investimento;
- [x] Autenticação com BasicAuth;

### 🛠 Tecnologias:
- PHP <img align="center" height="30" src="https://raw.githubusercontent.com/github/explore/ccc16358ac4530c6a69b1b80c7223cd2744dea83/topics/php/php.png">
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
- MySQL: <img align="center" height="30" src="https://raw.githubusercontent.com/github/explore/80688e429a7d4ef2fca1e82350fe8e3517d3494d/topics/mysql/mysql.png">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
- PHPUnit <img align="center" height="15" src="https://phpunit.de/img/phpunit.svg">

## Documentação da API

Link da documentação da API, gerada via Postman:
https://documenter.getpostman.com/view/12600470/UVsEV91b

## Como instalar e executar

- Passo 1: Faça um fork deste projeto;
- Passo 2: Faça um clone para o seu local, à partir do fork realizado no passo 1;
    - Passo 2.1: Caso esteja usando apache, faça o clone no diretório /var/www/html/ ou em um diretório de virtual host, caso tenha configurado um;
- Passo 3: Crie uma nova base de dados no MySQL;
- Passo 4: Execute os scripts disponíveis na pasta dbScripts, na base de dados criada no passo anterior;
    - Passo 4.1: Primeiro execute o script DDL 
    - Passo 4.2: Por fim execute o script o DML;
- Passo 5: Faça uma copia do arquivo .env.example na raiz do projeto e o renomeie para .env;
- Passo 6: Configure o arquivo .env preenchendo os parâmetros solicitados, de acordo com o seu ambiente;
    - Passo 6.1: Atente-se ao valor fornecido para o parâmetro APP_URL, caso você esteja executando o projeto com o servidor embutido do PHP;
- Passo 7: Acesse a URL do projeto de acordo com as configurações realizadas para o seu ambiente, por exemplo: http://localhost/projeto ou http://localhost:8000/projeto ou http://meuvirtualhost.local/projeto