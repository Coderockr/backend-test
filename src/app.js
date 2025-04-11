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
                <h2>Endpoints</h2>
                <ul>
                    <li>
                        <strong>Criação de um investimento</strong>
                        <p>Rota: <code>POST /api/investment</code></p>
                        <p>Exemplo de requisição:</p>
                        <pre>
curl -X POST http://localhost:8000/api/investment \
-H "Content-Type: application/json" \
-d '{"name": "John Doe", "value": 1000, "created_at": "2023-01-01"}'
                        </pre>
                    </li>
                    <li>
                        <strong>Listagem de investimentos</strong>
                        <p>Rota: <code>GET /api/investments/listInvestments</code></p>
                        <p>Exemplo de requisição:</p>
                        <pre>
curl -X GET "http://localhost:8000/api/investments/listInvestments?page=1&perPage=5"
                        </pre>
                    </li>
                    <li>
                        <strong>Visualização de um investimento</strong>
                        <p>Rota: <code>GET /api/investment/{investmentId}</code></p>
                        <p>Exemplo de requisição:</p>
                        <pre>
curl -X GET http://localhost:8000/api/investment/1
                        </pre>
                    </li>
                    <li>
                        <strong>Retirada de um investimento</strong>
                        <p>Rota: <code>PUT /api/investment/{investmentId}/withdraw</code></p>
                        <p>Exemplo de requisição:</p>
                        <pre>
curl -X PUT http://localhost:8000/api/investment/1/withdraw
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
