# Rockr Investment
## Api para teste de desenvolvedor back end
Essa API foi desenvolvida seguindo as especificações do teste de desenvolvedor backend pela Rockr.

As configurações do banco de dados e da Baseurl bem como todas demais configurações do sistemas estão no arquivo ".env", que se encontra na raiz do projeto.

Foi usado o framework PHP Codeingiter 4 para servir esta API e mySql como banco de dados.

A API permite a criação de um investimento, escolher uma data retroativa ou atual para esse investimento e calcular o lucro com base na data simulada. Permite também o saque total do valor investido, incidindo sobre o lucro a taxa de saque que poderá variar mediante ao tempo que o investimento esteve a capitalizar como abaixo descrito:

## TAXAS

Caso o investimento tenha menos de 12 meses, a taxa sobre o lucro será de 22.5%
Caso o investimento esteja entre 12 e 24 meses, a taxa sobre o lucro será de 18.5%
Caso o investimento tenha mais de 24 meses, a taxa sobre o lucro será de 15%

A documentação da API está descrita em sua home page.

## Início

A aplicação já vem com um virtual server, bastante apenas rodar o comando abaixo para gerar uma url onde todo o sistema irá rodar.

```sh
php spark serve
```
Foi criado também um seeder para popular a tabela user, não foi integrado sistema de autenticação.Sendo necessário apenas o código abaixo para rodar o seeder:
```sh
php spark db:seed
```

## Licensa

Todo o código deste teste foi criado segundo a instruções expostas pela equipe Rockr.
Sendo de livre acesso à equipe.
