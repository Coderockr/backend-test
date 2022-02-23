# Back End Test Project CODEROCKR

## libraries used
### Lumen
just Lumen, which is a micro php framework derived from Laravel.
I really like the structure of Laravel and its well done documentation. Since the framework has a lot of ready-made stuff that speeds up the work, such as the connection to the database, and the ORM, as well as configurations for tests.
## how to build
to terminal run the command below
```bash
  docker-compose up -d
```
with the containers running, access the php container and install the composer dependencies

```bash
  docker exec -it investment_portfolio_app bash
```
```bash
  composer install
```
if you need, permission in the log directory that is inside the storage

```bash
  chmod -R 777 storage/log
```
## api documentation
the server will run on `http://localhost:8000` 

### endpoints


## Investor
---
### GET ALL INVESTORS
![](https://img.shields.io/badge//api/v1/investor-gray?label=POST&labelColor=green)
#### Example URI
`GET http://localhost:8000/api/v1/investor`
#### URI Paramenters
#### Request
#### REsponse
---

