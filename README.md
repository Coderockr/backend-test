# Back End Test Project

Teste para desenvolvedor back-end na Coderockr, objetivando atender os requisitos da forma mais simples possível.

Foi utilizado no desenvolvimento desta aplicação o framework Laravel 8 e como SGBD o Postgres 13.

## Execução e testes

*   Faça o clone deste projeto
* Na raiz do projeto execute o comando <b>docker-compose up --build</b> para subir a aplicação. A aplicação estará disponpivel em http://127.0.0.1:8000
* Execução dos testes de unidade:
    - Execute o comaando <b>docker ps</b> para visualizar os container rodando e identificar o container da aplicação
    a saída do comando deverá ser algo como: <pre> CONTAINER ID   IMAGE                  COMMAND                  CREATED       STATUS          PORTS                                       NAMES
1ddb822f08c1   backend-test_backend   "bash -c 'composer i…"   2 hours ago   Up 22 seconds   0.0.0.0:8000->80/tcp, :::8000->80/tcp       backend-test_backend_1
efafcf4c470d   postgres:13            "docker-entrypoint.s…"   2 hours ago   Up 23 seconds   0.0.0.0:5433->5432/tcp, :::5433->5432/tcp   backend-test_db_1</pre>
    - execute o camando <b>docker exec -it {{id_container}} php artisan test</b>para rodar os testes, informando o id do container da aplicação. ex: docker exec -it 1ddb822f08c1 php artisan test

* A documentação da API está disponpivel em: https://documenter.getpostman.com/view/17234193/UVkgwyce
                

