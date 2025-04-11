/**
 * Project Overview
 * 
 * Este arquivo fornece uma visão geral do projeto, explicando os principais componentes,
 * suas funções e como eles interagem para formar a API de gerenciamento de investimentos.
 */

class ProjectOverview {
    /**
     * Arquivo principal: index.js
     * - Inicializa o servidor da aplicação.
     * - Configura a porta e importa o aplicativo principal (app.js).
     */
    static indexFile() {
        return `
        O arquivo index.js é o ponto de entrada da aplicação. Ele inicializa o servidor
        e configura a porta para escutar as requisições.
        `;
    }

    /**
     * Aplicação principal: app.js
     * - Define as rotas da API.
     * - Configura middlewares como validação de entrada e tratamento de erros.
     * - Implementa a documentação da API na rota /docs.
     */
    static appFile() {
        return `
        O arquivo app.js é o núcleo da aplicação. Ele define as rotas principais, como:
        - POST /investments: Criação de um investimento.
        - GET /investments: Listagem de investimentos.
        - POST /investments/:id/withdraw: Retirada de um investimento.
        Ele também configura middlewares para validação e tratamento de erros.
        `;
    }

    /**
     * Controlador: InvestmentController.js
     * - Gerencia as operações relacionadas a investimentos.
     * - Contém a lógica para criar, listar e retirar investimentos.
     */
    static investmentController() {
        return `
        O controlador InvestmentController.js organiza a lógica de negócios da API.
        Ele contém funções como:
        - createInvestment: Cria um novo investimento.
        - listInvestments: Lista todos os investimentos.
        - withdrawInvestment: Realiza a retirada de um investimento.
        `;
    }

    /**
     * Serviço: investmentService.js
     * - Contém a lógica de cálculo de rendimentos e impostos.
     * - Funções principais:
     *   - calculateEarnings: Calcula os rendimentos com base em juros compostos.
     *   - calculateTaxes: Calcula os impostos sobre os ganhos.
     */
    static investmentService() {
        return `
        O arquivo investmentService.js encapsula a lógica de cálculo financeiro.
        Ele é usado pelo controlador para calcular rendimentos e impostos.
        `;
    }

    /**
     * Middlewares
     * - validateInput.js: Valida os dados de entrada das requisições.
     * - errorHandler.js: Trata erros não capturados e retorna respostas apropriadas.
     */
    static middlewares() {
        return `
        Os middlewares são responsáveis por tarefas específicas:
        - validateInput.js: Garante que os dados enviados pelo cliente sejam válidos.
        - errorHandler.js: Captura erros e retorna uma resposta com status 500.
        `;
    }

    /**
     * Testes: investmentService.test.js
     * - Testa as funções de cálculo de rendimentos e impostos.
     * - Garante que a lógica financeira esteja correta.
     */
    static tests() {
        return `
        O arquivo investmentService.test.js contém testes unitários para validar
        as funções de cálculo financeiro. Ele utiliza o framework Jest.
        `;
    }

    /**
     * Configurações
     * - composer.json: Gerencia dependências do projeto PHP.
     * - .env: Define variáveis de ambiente, como APP_ENV e DATABASE_URL.
     */
    static configurations() {
        return `
        Os arquivos de configuração incluem:
        - composer.json: Lista as dependências do projeto.
        - .env: Define variáveis de ambiente para configuração do servidor.
        `;
    }

    /**
     * Documentação
     * - README.md: Explica como executar o projeto e testar a API.
     * - relatorio.md: Lista todos os arquivos do projeto e suas funções.
     */
    static documentation() {
        return `
        A documentação do projeto está disponível em dois arquivos:
        - README.md: Guia para execução e testes.
        - relatorio.md: Detalha os arquivos do projeto e suas funções.
        `;
    }
}

module.exports = ProjectOverview;
