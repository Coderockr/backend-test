<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rockr Investimento</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" />
</head>
<body>
<div class="container">
    <div class="jumbotron">
        <h1 class="display-4">Documentação Rockr API</h1>
        <p class="lead">
            Essa API foi desenvolvida seguindo as especificações aplicadas para o teste de desenvolvedor backend pela Rockr.

        </p>
        <p>
            As configurações do banco de dados e da Baseurl bem como todas demais configurações do sistemas estão no arquivo ".env", que se encontra na raiz do projeto.
        </p>
        <p>
            O sistema fornece a opção de migrations mesmo não sendo requisistado pelo teste.

        </p>
        <p>
            Foi usado o framework PHP Codeingiter 4 para servir esta API.
        </p>
        <p>
            A API permite a criação de um investimento, escolher uma data retroativa ou atual para esse investimento e calcular o lucro com base na data simulada. Permite também o saque total do valor investido, incidindo sobre o lucro a taxa de saque que poderá variar mediante ao tempo que o investimento esteve a capitalizar como abaixo descrito:
        </p>
        <p>
            <b>TAXAS</b>
            <br>
            Caso o investimento tenha menos de 12 meses, a taxa sobre o lucro será de 22.5%
            <br>
            Caso o investimento esteja entre 12 e 24 meses, a taxa sobre o lucro será de 18.5%
            <br>
            Caso o investimento tenha mais de 24 meses, a taxa sobre o lucro será de 15%
        </p>
        <p>
            <b>Iniciar o sistema</b>
            <br>
            Abra o terminal na raiz do projeto e digite o seguinte comando:
            <br>
            <b>php spark serve</b>
        </p>
        <p>
            <b>Seed</b>
            <br>
            Paga gerar um usuário de teste basta ainda na raiz do projeto digitar o seguinte comando:
            <br>
            <b>php spark db:seed</b>
        </p>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <h3 class="text-primary">Investimentos disponíveis</h3>
            <p>
                URL: <span class="text-danger"><?= base_url("investiments") ?></span>
            </p>
            <h3>Informação</h3>
            <table class="table table-striped">
                <tr>
                    <td>
                        Resource format
                    </td>
                    <td>
                        JSON
                    </td>
                </tr>
                <tr>
                    <td>Authentication</td>
                    <td>No</td>
                </tr>
                <tr>
                    <td>limited</td>
                    <td>No</td>
                </tr>
            </table>
        </div>
        <div class="col-sm-12">
            <h3>Methods</h3>
            <table class="table table-striped">
                <tr>
                    <td>
                        GET
                    </td>
                    <td>
                        Retorna todos investimentos disponíveis
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-sm-12">
            <h3 class="text-primary">Simulação</h3>
            <p>
                URL: <span class="text-danger"><?= base_url("simulate") ?></span>
            </p>
            <p>
                Simula investimento e saque
            </p>
            <h2>Informação</h2>
            <table class="table table-striped">
                <tr>
                    <td>
                        product_id
                    </td>
                    <td>
                        Obrigatório
                    </td>
                    <td>
                        1,2,3
                    </td>
                </tr>
                <tr>
                    <td>data_start</td>
                    <td>Obrigatório</td>
                    <td>YYYY-mm-dd > 2015-01-01</td>
                </tr>
                <tr>
                    <td>data_end</td>
                    <td>Obrigatório</td>
                    <td>YYYY-mm-dd > 2015-01-01 <br> YYYY-mm-dd < 2030-31-12</td>
                </tr>
                <tr>
                    <td>ammount</td>
                    <td>Obrigatório</td>
                    <td>150.30</td>
                </tr>
            </table>
        </div>
        <div class="col-sm-12">
            <h3>Method</h3>
            <table class="table table-striped">
                <tr>
                    <td>
                        POST
                    </td>
                    <td>
                        Retorna um objeto.
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-sm-12">
            <h3 class="text-primary">Histórico</h3>
            <p>
                URL: <span class="text-danger"><?= base_url("historico") ?></span>
            </p>

            <h2>Informação</h2>
            <table class="table table-striped">
                <tr>
                    <td>
                        Resource format
                    </td>
                    <td>
                        JSON
                    </td>
                </tr>
                <tr>
                    <td>Authentication</td>
                    <td>No</td>
                </tr>
                <tr>
                    <td>limited</td>
                    <td>No</td>
                </tr>
            </table>
        </div>
        <div class="col-sm-12">
            <h3>Method</h3>
            <table class="table table-striped">
                <tr>
                    <td>
                        GET
                    </td>
                    <td>
                        Retorna histórico de todas transações
    
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <h3 class="text-primary">Investimento</h3>
            <p>
                URL: <span class="text-danger"><?= base_url("investments") ?></span>
            </p>
            <p>
                Cria um investimento
            </p>
            <h2>Informação</h2>
            <table class="table table-striped">
                <tr>
                    <td>
                        product_id
                    </td>
                    <td>
                        Obrigatório
                    </td>
                    <td>
                        1,2,3
                    </td>
                </tr>
                <tr>
                    <td>data_start</td>
                    <td>Obrigatório</td>
                    <td>YYYY-mm-dd > 2015-01-01</td>
                </tr>
                <tr>
                    <td>ammount</td>
                    <td>Obrigatório</td>
                    <td>150.30</td>
                </tr>
            </table>
        </div>
        <div class="col-sm-12">
            <h3 class="text-primary">Saque</h3>
            <p>
                URL: <span class="text-danger"><?= base_url("investments") ?></span>
            </p>
            <p>
                Cria um saque total do investimento indicado
            </p>
            <h2>Informação</h2>
            <table class="table table-striped">
                <tr>
                    <td>
                        investiment_id
                    </td>
                    <td>
                        Obrigatório
                    </td>
                    <td>
                        89APO
                    </td>
                </tr>
                <tr>
                    <td>data_withdrawal</td>
                    <td>Obrigatório</td>
                    <td>A data dever superior a data do investimento e nunca superior a data atual.</td>
                </tr>
            </table>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(()=>{

})
</script>
