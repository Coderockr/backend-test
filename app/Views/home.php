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
    </div>
    <div class="row">
        <div class="col-sm-12">
            <h3>Investiments types</h3>
            <p>
                URL: <span class="text-danger"><?= base_url("investiments") ?></span>
            </p>
            <h3>Resource Information</h3>
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
                        Get All Investiments types
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-sm-12">
            <h3>Transactions ( investment )</h3>
            <p>
                URL: <span class="text-danger"><?= base_url("transactions") ?></span>
            </p>
            <h3>Resource Information</h3>
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
                <thead>
                <tr>
                    <th scope="col">TYPE</th>
                    <th scope="col">DESCRIPTION</th>
                    <th scope="col">PARAMETERS REQUIRED</th>
                </tr>
                </thead>
                <tr>
                    <td>
                        POST
                    </td>
                    <td>
                        Create an investment
                    </td>
                    <td>
                        Investimento ID, investment date create ("dd-mm-YYYY")
                    </td>
                </tr>
                <tr>
                    <td>
                        GET
                    </td>
                    <td>
                        Get All user transactions
                    </td>
                    <td>

                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-sm-12">
            <h3>Transactions ( withdrawn )</h3>
            <p>
                URL: <span class="text-danger"><?= base_url("transactions") ?></span>
            </p>
            <h3>Resource Information</h3>
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
                <thead>
                <tr>
                    <th scope="col">TYPE</th>
                    <th scope="col">DESCRIPTION</th>
                    <th scope="col">PARAMETERS REQUIRED</th>
                </tr>
                </thead>
                <tr>
                    <td>
                        POST
                    </td>
                    <td>
                        Create an withdrawn
                    </td>
                    <td>
                        Transaction ID, withdrawn date ("dd-mm-YYYY")
                    </td>
                </tr>
                <tr>
                    <td>
                        GET
                    </td>
                    <td>
                        Get All user transactions
                    </td>
                    <td>

                    </td>
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
