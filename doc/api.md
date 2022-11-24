# Documentação da API.

Esta é a documentação da API, do teste.

## Registro de usuario
### Rota para criar usuario.
- **POST** /user/registration

Exemplo de corpo da requisição:
```json
{
    "name": "Teste",
    "email": "teste@email.com",
    "password": "123456"
}
```

## Autenticação de usuário
### Rota para logar usuário.
- **POST** /api/login_check

Exemplo de corpo da requisição:
```json
{
    "username": "luan@email.com",
    "password": "123456"
}
```
Exemplo de resposta:
```json
{
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE2NjkyNDQyNzcsImV4cCI6MTY2OTI0Nzg3Nywicm9sZXMiOlsiUk9MRV9VU0VSIl0sInVzZXJuYW1lIjoibHVhbkBlbWFpbC5jb20ifQ.HWEVHLmZ0h7Gq2nYcbFJKMVHQkaDC1D9KZGa4V0Y6RM1eEWsLhGZzt3LGfoHV5srsYKiqXuQ-RR53EWQXd3Qux69ZckWyKp26RbRvX9oSHCgW8NegF5N7VLN_XaM4f5_2v22DtVRbtfy1bt3nuiTXS1qVbZ3yP3tzOSUfEZVL60kwBV0_5sBgBUfuw8U3Kbq2-v9Sdx2f_1bo1rRIF8u0tve3zERUpawm2naTaZkHLnclAWGF8Opubm5y2IJU41-iWRweb4x8ESSotc1InqPVSNUkXP_u_x3jeTC-RG2EZ0GC_aA6KDzq4SrrdSbibzGc17xWJ0a6dvfL9t9bIJ2hQ"
}
```

## Investimento
### Criar um investimento
- **POST** /api/investment/create

Exemplo de corpo da requisição:
```json
{
    "value": 1100.0,
    "created_at": "2022-04-08"
}
```

### Listar investimentos
- **POST** /api/investment/list

Para paginação, basta passar o numero da pagina na query string, segue o exemplo a seguir:
```
http://localhost:8000/api/investment/list?page=3
```

Exemplo de resposta:
```json
{
    "results": [
        {
          "id": "1ed6b829-19a5-6da8-b2ec-0b5b7674c963",
          "initial_value": 1100,
          "created_at": "2022-04-08",
          "date_of_withdrawal": null,
          "withdrawal_value": null
        }
    ],
    "page": "3"
}
```

### Mostrar um investimento
- **POST** /api/investment/show/{id}

Pase o Id do investimento como parametro da URL, segue um exemplo:
```
http://localhost:8000/api/investment/show/1ed6a089-9a7f-680a-90d2-5d5b90b9abe6
```

Exemplo de corpo da requisição:
```json
{
    "id": "1ed6b829-19a5-6da8-b2ec-0b5b7674c963",
    "initial_value": 1100,
    "created_at": "2022-04-08",
    "date_of_withdrawal": null,
    "withdrawal_value": null,
    "balance_expect": 1140.67
}
```

### Retirada de um investimento
- **POST** /api/investment/withdrawal/{id}

Pase o Id do investimento como parametro da URL, segue um exemplo:
```
http://localhost:8000/api/investment/withdrawal/1ed6a089-9a7f-680a-90d2-5d5b90b9abe6
```

Você pode informar uma data, esse paramentro é opcional:
```json
{
    "date": "2022-07-16"
}
```

Exemplo de resposta:
```json
{
    "success": {
        "message": "Retida com sucesso.",
        "withdrawal_value": 1028.65
    }
}
```
