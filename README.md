# Teste Técnico de Desenvolvimento

## Funcionalidades Implementadas

1. **Criação de um investimento**
   - **Rota**: `POST /investments`
   - **Descrição**: Cria um novo investimento com um proprietário, uma data de criação e um valor.
   - **Validações**:
     - A data de criação pode ser hoje ou no passado.
     - O valor do investimento não pode ser negativo.
   - **Como testar**:
     ```bash
     curl -X POST http://localhost:3000/investments -H "Content-Type: application/json" -d '{"owner": "John Doe", "creationDate": "2023-01-01", "value": 1000}'
     ```

2. **Visualização de um investimento**
   - **Rota**: `GET /investments`
   - **Descrição**: Lista todos os investimentos com o valor inicial e o saldo esperado.
   - **Como testar**:
     ```bash
     curl -X GET http://localhost:3000/investments
     ```

3. **Retirada de um investimento**
   - **Rota**: `POST /investments/:id/withdraw`
   - **Descrição**: Realiza a retirada de um investimento, aplicando impostos sobre os ganhos.
   - **Regras**:
     - O saque inclui o valor inicial e os ganhos.
     - Os impostos são aplicados sobre os ganhos.
     - Saques parciais não são suportados.
   - **Como testar**:
     ```bash
     curl -X POST http://localhost:3000/investments/1/withdraw
     ```

4. **Lista de investimentos de uma pessoa**
   - **Rota**: `GET /investments`
   - **Descrição**: Lista todos os investimentos de uma pessoa. Atualmente, a lista não possui paginação.
   - **Como testar**:
     ```bash
     curl -X GET http://localhost:3000/investments
     ```

## Regras de Negócio

- O investimento paga 0,52% ao mês, com ganhos compostos.
- Os impostos são aplicados apenas sobre os ganhos no momento da retirada.
- **Taxas de imposto**:
  - Menos de 1 ano: 22,5%
  - Entre 1 e 2 anos: 18,5%
  - Mais de 2 anos: 15%

## Como Executar

1. Instale as dependências:
   ```bash
   npm install
   ```

2. Inicie o servidor:
   ```bash
   npm start
   ```

3. Acesse a documentação da API:
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

## Créditos

Este desafio de codificação foi inspirado em kinvoapp/kinvo-back-end-test.