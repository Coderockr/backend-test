# Invest API
### Requisitos:
* PHP 7.6+
* Banco de dados MySQL 8+
## Instalação
```shell
git clone https://github.com/misterioso013/backend-test.git

cd backend-test

php -S localhost:8080
```
- Importe o arquivo `Database.sql` no seu banco de dados
- Agora é só renomear o arquivo `.env.example` para .`env` e alterar seus valores de acordo com o seu banco de dados MySQL.

## Documentação
- O único método aceito nas requisições é *POST*
- Exemplo de URL da API padrão: `localhost:8080/`
### Exemplo de uso:
```shell
curl -X POST http://localhost:8080/createUser -H "Content-Type: application/x-www-form-urlencoded" -d "name=Name+Aqui&cpf=12345678900"
```
### /createUser
Método resposável pela criação de um usuário no sistema

| Parâmetro| Tipo| Obrigatório | Descrição |
|----------|-----|----------|-----------------|
| name| string | Sim      | Nome do cliente |
| cpf | number/string | Sim | CPF válido do cliente |

  #### Resultado:
```json
{
  "status": "ok",
  "message": "User created successfully",
  "credentials": {
    "id": "1",
    "token": "db953f68da472h7be995db04b4186dfg"
  }
}
```
#### Sobre os campos
| Campo               | Descrição                                               |
|---------------------|---------------------------------------------------------|
| credentials > id    | Indetificador único do usuário                          |
| credentials > token | Chave de acesso para acessar os ivestimentos do usuário |

### /investment/create
Método resposável pela criação do investimento

| Parâmetro| Tipo | Obrigatório | Descrição |
|----------|-------|--|--------------------------------------------------------------------|
| user_id | integer | Sim | Identificador do usuário                                           |
| token | string | Sim | Token para validar a autenticação do usuário                       |
| value | float | Sim | Valor do investimento. Exemplo, para `R$1.234,56` digite `1234.56` |
| date | timestamp | Opcional | Data da criação do investimento. exemplo: 2022-03-06 21:20:37 |

#### Resultado:
```json
{
  "status":"ok",
  "message":"Investment created successfully",
  "details":{
    "investment_id":"1",
    "created_at":"2022-03-07 21:21:37"
  }
}
```
#### Sobre os campos
| Campo                   | Descrição                           |
|-------------------------|-------------------------------------|
| details > investment_id | Indetificador único do investimento |
| details > created_at    | Data da criação do investimento     |


### /investment/view
Este método retornará detalhes sobre um investimento

| Parâmetro| Tipo  | Obrigatório | Descrição                                                          |
|----------|-------|--|--------------------------------------------------------------------|
| user_id | integer | Sim | Identificador do usuário                                           |
| token | string | Sim | Token para validar a autenticação do usuário                       |
| id | integer | Sim | ID do investimento, que poderá ser recuperado através do método [listar](#/investment/list) |

#### Resultado:

```json
{
  "status":"ok",
  "id":"54",
  "initial_investment":"20000.00",
  "current_investment":"21248.00",
  "income":"1248.00",
  "withdrawal":"21017.12"
}
```
#### Sobre os campos

| Campo              | Descrição                                           |
|--------------------|-----------------------------------------------------|
| id                 | Indetificador único do investimento                 |
 | initial_investment | Valor inicial do investimento                       |
| current_investment | Valor atual do investimento com juros acrescentados |
| income             | Valor da renda total gerada até o momento           |
| withdrawal         | Valor disponível para a retirada                    |

### /investment/list
Este método retornará uma lista com todos os investimentos

| Parâmetro| Tipo  | Obrigatório | Descrição                                                          |
|----------|-------|--|--------------------------------------------------------------------|
| user_id | integer | Sim | Identificador do usuário                                           |
| token | string | Sim | Token para validar a autenticação do usuário                       |
| page | integer | Opcional | Número da página que deseja acessar, são exibidos 5 resultados por página |

#### Resultado:

```json
{
  "status":"ok",
  "pages":8,
  "results":[
    {
      "id":"54",
      "initial_investment":"20000.00",
      "current_investment":"21248.00",
      "income":"1248.00",
      "withdrawal":"21017.12"
    },
    {
      "id":"55",
      "initial_investment":"100000.00",
      "current_investment":"100000.00",
      "income":"0.00",
      "withdrawal":"100000.00"
    }
  ]
}
```
#### Sobre os campos

| Campo                               | Descrição                                            |
|-------------------------------------|------------------------------------------------------|
| pages                               | Número de páginas disponíveis                        |
| result                              | Retonar um Array com os detalhes de cada investimeto |
| result > valor > initial_investment | Valor inicial do investimento                        |
| result > valor > current_investment | Valor atual do investimento com juros acrescentados  |
| result > valor > income             | Valor da renda total gerada até o momento            |
| result > valor > withdrawal         | Valor disponível para a retirada                     |

### investment/withdrawal
Método resposável por fazer a retirada de um investimento

| Parâmetro| Tipo | Obrigatório | Descrição |
|---------|-------|--|------------------------------------|
| user_id | integer | Sim | Identificador do usuário |
| token | string | Sim | Token para validar a autenticação do usuário  |
| id | integer | Sim | ID do investimento que deseja retirar |
| date | timestamp | Opcional | Data da retirada do investimento. Exemplo: 2022-03-06 21:20:37 |


#### Resultado:
```json
{
 "status":"ok",
 "message":"Withdrawal successful",
 "initial_investment":"100000.00",
 "withdrawal":"121216.00"
}
```
#### Sobre os campos
| Campo              | Descrição                                      |
|--------------------|------------------------------------------------|
| initial_investment | Valor do investimento inical                   |
| withdrawal         | Valor da retirada já com os impostos aplicados |