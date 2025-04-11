# Teste Técnico de Desenvolvimento

## Funcionalidades Implementadas

1. **Criação de um investimento**
   - **Rota**: `POST /api/investment`
   - **Descrição**: Cria um novo investimento com um proprietário, uma data de criação e um valor.
   - **Validações**:
     - O nome do proprietário é obrigatório.
     - O valor do investimento não pode ser negativo.
     - A data de criação pode ser hoje ou no passado.
   - **Exemplo de requisição**:
     ```bash
     curl -X POST http://localhost:8000/api/investment \
     -H "Content-Type: application/json" \
     -d '{"name": "John Doe", "value": 1000, "created_at": "2023-01-01"}'
     ```

2. **Listagem de investimentos**
   - **Rota**: `GET /api/investments/listInvestments`
   - **Descrição**: Lista todos os investimentos cadastrados, com suporte a paginação.
   - **Parâmetros opcionais**:
     - `page`: Número da página (padrão: 1).
     - `perPage`: Número de itens por página (padrão: 10).
   - **Exemplo de requisição**:
     ```bash
     curl -X GET "http://localhost:8000/api/investments/listInvestments?page=1&perPage=5"
     ```

3. **Visualização de um investimento**
   - **Rota**: `GET /api/investment/{investmentId}`
   - **Descrição**: Retorna os detalhes de um investimento específico.
   - **Exemplo de requisição**:
     ```bash
     curl -X GET http://localhost:8000/api/investment/1
     ```

4. **Retirada de um investimento**
   - **Rota**: `PUT /api/investment/{investmentId}/withdraw`
   - **Descrição**: Realiza a retirada de um investimento, aplicando impostos sobre os ganhos.
   - **Regras**:
     - O saque inclui o valor inicial e os ganhos.
     - Os impostos são aplicados sobre os ganhos.
     - Saques parciais não são suportados.
   - **Exemplo de requisição**:
     ```bash
     curl -X PUT http://localhost:8000/api/investment/1/withdraw
     ```

## Regras de Negócio

- O investimento paga 0,52% ao mês, com ganhos compostos.
- Os impostos são aplicados apenas sobre os ganhos no momento da retirada.
- **Taxas de imposto**:
  - Menos de 1 ano: 22,5%
  - Entre 1 e 2 anos: 18,5%
  - Mais de 2 anos: 15%

## Como Executar

1. Instale as dependências do projeto:
   ```bash
   composer install
   npm install
   ```
2. Configure o banco de dados no arquivo .env:
Exemplo de configuração para SQLite:
DATABASE_URL="sqlite:///%kernel.project_dir%/var/data.db"

3. Execute as migrações para criar as tabelas no banco de dados:
   php bin/console doctrine:migrations:migrate
4.  Inicie o servidor:

npm start

5. Acesse a documentação da API:
   - URL: http://localhost:3000/docs

## Relatório de Arquivos

Para uma visão detalhada de cada arquivo do projeto e suas funções, consulte o arquivo [relatorio.md](./relatorio.md).

## Visão Geral do Projeto

Para uma explicação detalhada sobre os principais componentes do projeto e como eles interagem, consulte o arquivo [ProjectOverview.js](./src/ProjectOverview.js).

## Testes

Execute os testes com:
```bash
npm test
```

Documentação da API
Acesse a documentação completa da API em: http://localhost:8000/docs