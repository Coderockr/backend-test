# Relatório de Arquivos do Projeto

## Arquivos e suas Funções

### 1. **index.js**
- **Descrição**: Arquivo principal que inicializa o servidor da aplicação.
- **Função**: Configura o servidor para escutar na porta especificada e importa o aplicativo principal.

---

### 2. **src/app.js**
- **Descrição**: Arquivo principal da aplicação que define as rotas e a lógica da API.
- **Função**:
  - Define as rotas para criar, listar e retirar investimentos.
  - Implementa a documentação da API na rota `/docs`.
  - Configura middlewares como o de log e tratamento de erros.

---

### 3. **src/middlewares/validateInput.js**
- **Descrição**: Middleware para validação de entrada de dados.
- **Função**:
  - Valida o proprietário, a data de criação e o valor do investimento.
  - Garante que a data de criação não seja no futuro e que o valor não seja negativo.

---

### 4. **src/middlewares/errorHandler.js**
- **Descrição**: Middleware para tratamento de erros.
- **Função**:
  - Captura erros não tratados e retorna uma resposta com status `500` e uma mensagem de erro.

---

### 5. **src/services/investmentService.js**
- **Descrição**: Serviço que contém a lógica de cálculo de rendimentos e impostos.
- **Função**:
  - `calculateEarnings`: Calcula os rendimentos com base em juros compostos.
  - `calculateTaxes`: Calcula os impostos sobre os ganhos com base no tempo do investimento.

---

### 6. **tests/investmentService.test.js**
- **Descrição**: Arquivo de testes unitários para o serviço de investimentos.
- **Função**:
  - Testa o cálculo de rendimentos e impostos para diferentes cenários.

---

### 7. **README.md**
- **Descrição**: Arquivo de documentação principal do projeto.
- **Função**:
  - Explica as funcionalidades implementadas, como executar o projeto e como testar a API.

---

### 8. **relatorio.md**
- **Descrição**: Este arquivo.
- **Função**:
  - Lista todos os arquivos do projeto com suas respectivas descrições e funções.

---

### 9. **composer.json**
- **Descrição**: Arquivo de configuração do Composer.
- **Função**:
  - Define as dependências do projeto PHP e suas versões.
  - Configura o autoload para as classes do projeto.

---

### 10. **composer.lock**
- **Descrição**: Arquivo gerado automaticamente pelo Composer.
- **Função**:
  - Garante que as versões exatas das dependências sejam instaladas.

---

### 11. **.env**
- **Descrição**: Arquivo de configuração de variáveis de ambiente.
- **Função**:
  - Define variáveis como `APP_ENV`, `APP_SECRET` e `DATABASE_URL`.

---

### 12. **src/controllers/InvestmentController.js**
- **Descrição**: Controlador responsável por gerenciar as operações relacionadas a investimentos.
- **Função**:
  - Contém a lógica para criar, listar e retirar investimentos.

---

### 13. **src/entities/Investment.js**
- **Descrição**: Entidade que representa um investimento.
- **Função**:
  - Define a estrutura de dados de um investimento, incluindo propriedades como `id`, `owner`, `creationDate`, `value` e `withdrawn`.

---

## Estrutura do Projeto

```
TesteTecnicoDesenvolvimento/
├── index.js
├── README.md
├── relatorio.md
├── composer.json
├── composer.lock
├── .env
├── src/
│   ├── app.js
│   ├── controllers/
│   │   ├── InvestmentController.js
│   ├── entities/
│   │   ├── Investment.js
│   ├── middlewares/
│   │   ├── errorHandler.js
│   │   ├── validateInput.js
│   ├── services/
│   │   ├── investmentService.js
├── tests/
│   ├── investmentService.test.js
```
