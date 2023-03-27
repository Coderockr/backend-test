
# Desafio Api de Investimento Coderockr

Neste desafio deverás construir uma API para uma aplicação que armazena e gere investimentos, a mesma deverá ter as seguintes funcionalidades: 
## Requerido
- [Docker](https://www.docker.com/)
- [Docker Composer](https://docs.docker.com/compose/)
- [Make](https://linuxhint.com/install-use-make-ubuntu/)

## 👩‍💻  Sobre o desafio
Nesta aplicação foi desenvolvido com a maior parte dos recursos que o próprio framework já fornece, o único pacote que utilizei foi o L5-Swagger para criar uma documentação básica da api.

No container da aplicação adicionei um crontab, de tempos em tempos o serviço é executado aplicando ganhos em todos os investimentos que estiverem com status ativo. Isso acontece logo após o container estiver levantado, de 5 mim em 5 mim são analisados/aplicados os ganhos conforme a regra de negócio.

A partir da rota `[GET]/api/owners` com o retorno da consulta, vocês já terão insumos para prosseguir com os testes em todas as rodas, como: Criar, Consultar, Sacar, Remove `owners` e `investments`

## Executar localmente

Clonar o projeto
```bash
  git clone https://github.com/bladellano/backend-test.git --branch development
```

Vá para o diretório do projeto
```bash
  cd backend-test
```

## Opções para construção do projeto
### Opção 01 - com Makefile:

```bash
  make install
```

### Opção 02 - sem Makefile:
```bash
  cd laravel-app/ && rm .env -f && cp .env.example .env && cd ..

  docker-compose up -d

  docker exec coderockr-app bash -c 'composer update && php artisan key:generate'	

  docker exec -t coderockr-app bash -c 'chown -R www-data:www-data /var/www/html/storage'

  docker exec coderockr-app bash -c 'php artisan migrate && php artisan db:seed'	

  docker exec -t coderockr-app bash -c '/etc/init.d/cron start'

```
Após executar todas essa etapas e o projeto já estiver criado, clique aqui para ver a aplicação funcionando http://127.0.0.1:8080/api 

Na raíz do projeto existe uma colletion para importar no Insomnia `coderockr.json`

# Comandos Make para acesso rápido

#### Entrar no bash da aplicação
```bash
make in
```
#### Executar tests
```bash
make test
```
#### Execução programada
```bash
make schedule-run
```
#### Log da execução programada
```bash
make cron-log
```

#### Parar a execução programada
```bash
make cron-stop
```

# Documentação da API

Link [http://127.0.0.1:8080/api/doc](http://127.0.0.1:8080/api/doc)

# Desenvolvido por

Esta aplicação foi desenvolvida por [Caio Dellano Nunes da Silva.](https://dellanosites.com.br/) 

[![linkedin](https://img.shields.io/badge/linkedin-0A66C2?style=for-the-badge&logo=linkedin&logoColor=white)](https://www.linkedin.com/in/bladellano/)


