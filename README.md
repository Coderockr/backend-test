
# Desafio Api de Investimento Coderockr

Neste desafio deverás construir uma API para uma aplicação que armazena e gere investimentos, a mesma deverá ter as seguintes funcionalidades: 
## Requerido
- [Docker](https://www.docker.com/)
- [Docker Composer](https://docs.docker.com/compose/)
- [Make](https://linuxhint.com/install-use-make-ubuntu/)

## 👩‍💻  Sobre mim
Sou organizado e perfeccionista, preocupo-me com a qualidade. Gosto de ambientes estruturados com regras claras. Quando recebo uma tarefa, procuro executá-la com precisão e atenção aos detalhes. Sou calmo e bom ouvinte, acompanho os processos sempre que possível.

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


