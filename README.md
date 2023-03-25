# **Investimento API**

Api Desenvolvida que tem por objetivo criar um investimento, visualizar e sacar investimentos.

## **Antes de iniciar**

Assim que rodar o projeto execute os seguintes passos abaixo:

-   Rode o comando <code>**composer install**</code>, para instalar as dependências do projeto no **vendor**.
-   Entre no seu **.env** e configure seu banco de dados.
-   Rode as migrations com as **seeds**.

## **Como usar**

#### **Obs. Siga os passos abaixo corretamente para melhor execução da aplicação.**

-   **1 - Criação de um Investimento**

        -   endpoint: [POST] /api/v1/investimento
        -   campos: [investimento | investidor_id | valor_inicial | data_criacao]

-   **2 - Visualização de um Investimento com Ganhos e Saldo Esperado**

        -   endpoint: [GET] /api/v1/investimento/{investimento}/{investidor}/visualizar

-   **3 - Simulação de Retirada de um Investimento**

        -   endpoint: [GET] /api/v1/investimento/{investimento}/{data_retirada}/{investidor}/retirar/simulacao

-   **4 - Retirada de um Investimento**

        -   endpoint: [POST] /api/v1/investimento/retirar
        -   campos: [investimento | data_retirada | investidor]

-   **5 - Visualização de todos os Investimentos do Investidor**

        -  endpoint: [GET] /api/v1/investimento/1/investimentos

Obs. O endpoint **[2]** deve ser executado antes dos endpoints **[3, 4]**,
pois o mesmo devolve valores que serão utilizados pelos os dois endpoints respectivamente.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 2000 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Documentação

A documentação da API está escrita no **Swagger | SwaggerUI** e também no **Postman**, nos seguintes endereços:

-   [Swagger Investimento API](http://localhost:8000/api/swagger/docs)

    -   Acesso a rota **/api/swagger/docs**

-   [Postman Investimento API](https://solar-crescent-99498.postman.co/workspace/1c09947e-202d-443f-878c-267f6f3c8603/documentation/19838517-6c67f4e1-430f-48dc-ae8a-c366b253aec1)
    -   Acesse o diretório do projeto e procure o arquivo
        **ApiInvestimento.json**.
    -   Importe o mesmo para o seu Postman.

## Pacotes Externos

Sem libs externas no projeto, somente as do próprio framework.

## Licença

Licença [MIT](https://opensource.org/licenses/MIT).
