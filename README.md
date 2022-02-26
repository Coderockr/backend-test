# Back End Test
This is a RESTful SOLID API!
## Dependencies
### Docker
lorem ipsum
#### docker-compose
### Express
lorem ipsum

### React
lorem ipsum (In fact not yet)

### Database(Not selected)
lorem

## How to run?

At first, clone the repo:

```bash
git clone https://github.com/lrNas/backend-test
```
Then, install docker (documentation on https://docs.docker.com/engine/install/ubuntu/)

it whould be necessary to have docker-compose. Install it with the following:

```bash
sudo apt install docker-compose
```

Move on the cloned repo folder, then:

```bash
docker build -t dockernode 
docker run --network-alias=THEADDRESYOUWISH -p 3000:3000 -p 3030:3030 -d THENAMEYOUWISH
```

#### To stop the execution
```bash
docker ps
```
Find on the output the CONTAINER ID, then:
```bash
docker stop CONTAINERID
```

## Api documentation
The server will run on the adress you specify on the docker run command. If you just copy and pasted, it will be `http://THEADDRESSYOUWISH:3030`. Let's assume that for the next examples.

### Endpoints

#### `http://THEADDRESSYOUWISH:3030/investment` 
#### `http://THEADDRESSYOUWISH:3030/owner` 
#### `http://THEADDRESSYOUWISH:3030/withdraw` 
## Response default

All the responses from API have the following format:

` boolean`
` string`
` number`
` Array`

### Sucessfull

```json
  "error": false,
  "message": "Some Positive Message",
  "status": 200,
  "objects": [
   ...
  ]

```
` error = boolean`
` message = string`
` status = number`
` objects = Array`

### Fail:

```json
"error": true,
  "message": "Some Negative Message",
  "status": 406,
  "errorList": [
          ...
  ]
```
` error = boolean`
` message = string`
` status = number`
` errorList = Array from Error`


## OWNER
### GET ALL OWNERS
#### Example URI
`GET http://THEADDRESSYOUWISH:3030/owner`

#### Response
```json
{
  "error": false,
  "message": "All owners sucessfully loaded",
  "status": 200,
  "objects": [
    {
      "_ownerId": 1,
      "_firstName": "User",
      "_lastName": "First",
      "_email": "data@fake.com",
      "_phoneNumber": 23912424161
    },...
  ]
}
```

### GET A OWNER BY ID
#### Example URI
`GET http://THEADDRESSYOUWISH/owner/?id={ownerId}`
#### URI Paramenters
` ownerId =  number`
#### Headers 
`Content-Type: application/json`

#### Response
```json
"error": false,
  "message": "Owner sucessfully loaded",
  "status": 200,
  "objects": [
    {
      "_ownerId": 1,
      "_firstName": "User",
      "_lastName": "First",
      "_email": "dek@fake.com",
      "_phoneNumber": 2351201215
    }
  ]
```
### GET A PAGE OF OWNERS
#### Example URI
`GET http://THEADDRESSYOUWISH/owner/?page={pageNumber}`
#### URI Paramenters
` pageNumber = number`
#### Headers 
`Content-Type: application/json`
#### Response
```json
  "error": false,
  "message": "Owners page sucessfully loaded",
  "status": 200,
  "objects": [
    {
      "_ownerId": 1,
      "_firstName": "Ann",
      "_lastName": "Grace",
      "_email": "lasd@fake.com",
      "_phoneNumber": 23946545215
    },...
  ]
  ]

```
## INVESTMENT

### GET A ALL INVESTMENTS
#### Example URI
`GET http://THEADDRESSYOUWISH/investment`
#### Response
```json

  "error": false,
  "message": "All investments sucessfully loaded",
  "status": 200,
  "objects": [
    {
      "_investmentId": 1,
      "_ownerId": 1,
      "_creationDate": "2022-01-27T08:12:00.000Z",
      "_initialAmount": 179.21,
      "_atualAmount": 179.21,
      "todayExpected": 179.21
    },...
  ]

  


```


