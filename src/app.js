const express = require('express');
const errorHandler = require('./middlewares/errorHandler');
const validateInput = require('./middlewares/validateInput');
const { calculateEarnings, calculateTaxes } = require('./services/investmentService');

const app = express();
app.use(express.json());

// Middleware de log
app.use((req, res, next) => {
    console.log(`${req.method} ${req.url}`);
    next();
});

// Lista em memória para armazenar os investimentos
const investments = [];

// Rota para o caminho raiz
app.get('/', (req, res) => {
    res.status(200).send({ message: 'Bem-vindo à API de Investimentos!' });
});

// Rota para listar investimentos formatados como HTML
app.get('/investments', (req, res) => {
    const formattedInvestments = investments.map((investment) => `
        <tr>
            <td>${investment.id}</td>
            <td>${investment.owner}</td>
            <td>${investment.creationDate}</td>
            <td>R$ ${investment.value.toFixed(2)}</td>
        </tr>
    `).join('');

    const html = `
        <html>
            <head>
                <title>Investimentos</title>
            </head>
            <body>
                <h1>Lista de Investimentos</h1>
                <table border="1">
                    <tr>
                        <th>ID</th>
                        <th>Proprietário</th>
                        <th>Data de Criação</th>
                        <th>Valor</th>
                    </tr>
                    ${formattedInvestments}
                </table>
            </body>
        </html>
    `;
    res.status(200).send(html);
});

// Rota para criar um investimento
app.post('/investments', validateInput, (req, res) => {
    const { owner, creationDate, value } = req.body;
    const newInvestment = { id: investments.length + 1, owner, creationDate, value, withdrawn: false };
    investments.push(newInvestment);
    res.status(201).send({ message: 'Investimento criado com sucesso!', investment: newInvestment });
});

// Rota para retirar um investimento
app.post('/investments/:id/withdraw', (req, res) => {
    const { id } = req.params;
    const investment = investments.find((inv) => inv.id === parseInt(id));

    if (!investment) {
        return res.status(404).send({ error: 'Investimento não encontrado.' });
    }

    if (investment.withdrawn) {
        return res.status(400).send({ error: 'Este investimento já foi retirado.' });
    }

    const creationDate = new Date(investment.creationDate);
    const currentDate = new Date();
    const months = (currentDate.getFullYear() - creationDate.getFullYear()) * 12 + (currentDate.getMonth() - creationDate.getMonth());

    const earnings = calculateEarnings(investment.value, months);
    const taxes = calculateTaxes(earnings, months);
    const total = investment.value + earnings - taxes;

    investment.withdrawn = true;

    res.status(200).send({
        message: 'Retirada realizada com sucesso!',
        investment: {
            id: investment.id,
            owner: investment.owner,
            creationDate: investment.creationDate,
            value: investment.value,
            earnings: earnings.toFixed(2),
            taxes: taxes.toFixed(2),
            total: total.toFixed(2),
        },
    });
});

// Rota para exibir a documentação completa da API
app.get('/docs', (req, res) => {
    const html = `
        <html>
            <head>
                <title>Documentação da API</title>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; margin: 20px; }
                    h1, h2 { color: #333; }
                    pre { background: #f4f4f4; padding: 10px; border: 1px solid #ddd; }
                    table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
                    table, th, td { border: 1px solid #ddd; }
                    th, td { padding: 8px; text-align: left; }
                </style>
            </head>
            <body>
                <h1>Documentação da API de Investimentos</h1>
                <h2>Funcionalidades</h2>
                <ul>
                    <li>
                        <strong>Criação de um investimento</strong>
                        <p>Implementado na rota <code>POST /investments</code>.</p>
                        <p>Validações:</p>
                        <ul>
                            <li>A data de criação pode ser hoje ou no passado.</li>
                            <li>O valor do investimento não pode ser negativo.</li>
                        </ul>
                        <p>Como testar:</p>
                        <pre>
curl -X POST http://localhost:3000/investments -H "Content-Type: application/json" -d '{"owner": "John Doe", "creationDate": "2023-01-01", "value": 1000}'
                        </pre>
                    </li>
                    <li>
                        <strong>Visualização de um investimento</strong>
                        <p>Implementado na rota <code>GET /investments</code>.</p>
                        <p>Mostra o valor inicial e o saldo esperado.</p>
                        <p>Como testar:</p>
                        <pre>
curl -X GET http://localhost:3000/investments
                        </pre>
                    </li>
                    <li>
                        <strong>Retirada de um investimento</strong>
                        <p>Implementado na rota <code>POST /investments/:id/withdraw</code>.</p>
                        <p>Regras:</p>
                        <ul>
                            <li>O saque inclui o valor inicial e os ganhos.</li>
                            <li>Os impostos são aplicados sobre os ganhos.</li>
                            <li>Saques parciais não são suportados.</li>
                        </ul>
                        <p>Como testar:</p>
                        <pre>
curl -X POST http://localhost:3000/investments/1/withdraw
                        </pre>
                    </li>
                    <li>
                        <strong>Lista de investimentos de uma pessoa</strong>
                        <p>Implementado na rota <code>GET /investments</code>.</p>
                        <p>Atualmente, a lista não possui paginação, mas pode ser estendida.</p>
                        <p>Como testar:</p>
                        <pre>
curl -X GET http://localhost:3000/investments
                        </pre>
                    </li>
                </ul>
                <h2>Regras de Negócio</h2>
                <ul>
                    <li>O investimento paga 0,52% ao mês, com ganhos compostos.</li>
                    <li>Os impostos são aplicados apenas sobre os ganhos no momento da retirada.</li>
                    <li>Taxas de imposto:
                        <ul>
                            <li>Menos de 1 ano: 22,5%</li>
                            <li>Entre 1 e 2 anos: 18,5%</li>
                            <li>Mais de 2 anos: 15%</li>
                        </ul>
                    </li>
                </ul>
                <h2>Relatório de Arquivos</h2>
                <table>
                    <tr>
                        <th>Arquivo</th>
                        <th>Descrição</th>
                        <th>Função</th>
                    </tr>
                    <tr>
                        <td><code>index.js</code></td>
                        <td>Arquivo principal que inicializa o servidor da aplicação.</td>
                        <td>Configura o servidor para escutar na porta especificada e importa o aplicativo principal.</td>
                    </tr>
                    <tr>
                        <td><code>src/app.js</code></td>
                        <td>Arquivo principal da aplicação que define as rotas e a lógica da API.</td>
                        <td>
                            Define as rotas para criar, listar e retirar investimentos.<br>
                            Implementa a documentação da API na rota <code>/docs</code>.<br>
                            Configura middlewares como o de log e tratamento de erros.
                        </td>
                    </tr>
                    <tr>
                        <td><code>src/middlewares/validateInput.js</code></td>
                        <td>Middleware para validação de entrada de dados.</td>
                        <td>
                            Valida o proprietário, a data de criação e o valor do investimento.<br>
                            Garante que a data de criação não seja no futuro e que o valor não seja negativo.
                        </td>
                    </tr>
                    <tr>
                        <td><code>src/middlewares/errorHandler.js</code></td>
                        <td>Middleware para tratamento de erros.</td>
                        <td>
                            Captura erros não tratados e retorna uma resposta com status <code>500</code> e uma mensagem de erro.
                        </td>
                    </tr>
                    <tr>
                        <td><code>src/services/investmentService.js</code></td>
                        <td>Serviço que contém a lógica de cálculo de rendimentos e impostos.</td>
                        <td>
                            <code>calculateEarnings</code>: Calcula os rendimentos com base em juros compostos.<br>
                            <code>calculateTaxes</code>: Calcula os impostos sobre os ganhos com base no tempo do investimento.
                        </td>
                    </tr>
                    <tr>
                        <td><code>tests/investmentService.test.js</code></td>
                        <td>Arquivo de testes unitários para o serviço de investimentos.</td>
                        <td>Testa o cálculo de rendimentos e impostos para diferentes cenários.</td>
                    </tr>
                    <tr>
                        <td><code>README.md</code></td>
                        <td>Arquivo de documentação principal do projeto.</td>
                        <td>Explica as funcionalidades implementadas, como executar o projeto e como testar a API.</td>
                    </tr>
                    <tr>
                        <td><code>relatorio.md</code></td>
                        <td>Arquivo de relatório.</td>
                        <td>Lista todos os arquivos do projeto com suas respectivas descrições e funções.</td>
                    </tr>
                    <tr>
                        <td><code>composer.json</code></td>
                        <td>Arquivo de configuração do Composer.</td>
                        <td>Define as dependências do projeto PHP e suas versões.<br>Configura o autoload para as classes do projeto.</td>
                    </tr>
                    <tr>
                        <td><code>composer.lock</code></td>
                        <td>Arquivo gerado automaticamente pelo Composer.</td>
                        <td>Garante que as versões exatas das dependências sejam instaladas.</td>
                    </tr>
                    <tr>
                        <td><code>.env</code></td>
                        <td>Arquivo de configuração de variáveis de ambiente.</td>
                        <td>Define variáveis como <code>APP_ENV</code>, <code>APP_SECRET</code> e <code>DATABASE_URL</code>.</td>
                    </tr>
                </table>
            </body>
        </html>
    `;
    res.status(200).send(html);
});

// Middleware para rotas inexistentes
app.use((req, res) => {
    res.status(404).send({ error: 'Rota não encontrada.' });
});

// Adicionar o middleware de tratamento de erros como o último middleware
app.use(errorHandler);

module.exports = app;
