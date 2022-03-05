# Invest API

## Documentação

### /createUser
Método resposável pela criação de um usuário no sistema


- Método: **POST**
- Parametros:
  - name
  - CPF

  **Resultado:**
```json
{
  "status": "ok",
  "message": "User created successfully",
  "credentials": {
    "id": "1",
    "token": "db953f68da472f7be995db04b4186dfg"
  }
}
```