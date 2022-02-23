# Back End Test Project CODEROCKR

## Libraries used
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
## Api documentation
the server will run on `http://localhost:8000` 

### Endpoints

---
## Investor
### GET ALL INVESTORS
#### Example URI
`GET http://localhost:8000/api/v1/investor`
#### URI Paramenters
#### Headers 
#### Request
#### Response
```json
{
    "message": "success",
    "error": false,
    "data": [
        {
	    "id": "9d352490-7938-46b1-aca4-6d71f527903f",
	    "name": "paulo henrique",
	    "created_at": "2022-02-20T21:55:56.000000Z",
	    "updated_at": "2022-02-20T21:55:56.000000Z"
	}
    ]
}
```
### CREATE A INVESTOR
#### Example URI
`POST http://localhost:8000/api/v1/investor`
#### URI Paramenters
#### Headers 

`Content-Type: application/json`

#### Request
```json
{
    "name": "John"
}
```
#### Response
```json
{
    "message": "investor successfully created!",
    "error": false,
    "data": [
        {
	    "id": "ab57280c-decf-4f33-9d00-07e85e5667b7",
	    "name": "John",
	    "updated_at": "2022-02-22T23:05:21.000000Z",
	    "created_at": "2022-02-22T23:05:21.000000Z"
	}
    ]
}
```
---

---
## Investment
### CREATE A INVESTMENT
#### Example URI
`GET http://localhost:8000/api/v1/investor/{investor_id}/investiment`
#### URI Paramenters
` **investor_id ** string`
#### Headers 
`Content-Type: application/json`
#### Request
```json
{
    "amount": 222.2
}
#### Response
```json
{
    "message": "success",
    "error": false,
    "data": [
        {
	    "id": "88d50ff6-9b08-4440-a130-79e295f8fd6f",
	    "initial_investment": 222.2,
	    "investment_reference_date": "2022-02-22T23:10:48.231411Z",
	    "created_at": "2022-02-22T23:09:02.000000Z",
	    "updated_at": "2022-02-22T23:10:48.000000Z",
	    "investor_id": "ab57280c-decf-4f33-9d00-07e85e5667b7"
	}
    ]
}
```
### CREATE A INVESTOR
#### Example URI
`POST http://localhost:8000/api/v1/investor`
#### URI Paramenters
#### Headers 
`Content-Type: application/json`
#### Request
```json
{
    "name": "John"
}
```
#### Response
```json
{
    "message": "investor successfully created!",
    "error": false,
    "data": [
        {
	    "id": "ab57280c-decf-4f33-9d00-07e85e5667b7",
	    "name": "John",
	    "updated_at": "2022-02-22T23:05:21.000000Z",
	    "created_at": "2022-02-22T23:05:21.000000Z"
	}
    ]
}
```
---

