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

### DATABASE (Not selected)
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

#### TO STOP THE EXECUTION
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
---

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
    },{
      "_ownerId": 2,
      "_firstName": "Another",
      "_lastName": "User",
      "_email": "email2@fake.com",
      "_phoneNumber": 21954487654
    },
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
    },
    {
      "_ownerId": 2,
      "_firstName": "Carlos",
      "_lastName": "Rodrigues",
      "_email": "lasdoep@fake.com",
      "_phoneNumber": 29988545215
    },
    {
      "_ownerId": 3,
      "_firstName": "Jhonny",
      "_lastName": "Bass",
      "_email": "fooos@fake.com",
      "_phoneNumber": 11988545215
    },
    {
      "_ownerId": 4,
      "_firstName": "Melvin",
      "_lastName": "Roth",
      "_email": "keka@fake.com",
      "_phoneNumber": 11988415215
    },
    {
      "_ownerId": 5,
      "_firstName": "Guy",
      "_lastName": "Lee",
      "_email": "sossc@fake.com",
      "_phoneNumber": 11983615215
    },
    {
      "_ownerId": 6,
      "_firstName": "Hank",
      "_lastName": "Shreder",
      "_email": "scllas@fake.com",
      "_phoneNumber": 11936178954
    },
    {
      "_ownerId": 7,
      "_firstName": "Marina",
      "_lastName": "White",
      "_email": "epllas@fake.com",
      "_phoneNumber": 1155946351
    },
    {
      "_ownerId": 8,
      "_firstName": "Anna",
      "_lastName": "Braga",
      "_email": "jetd@fake.com",
      "_phoneNumber": 1158696351
    },
    {
      "_ownerId": 9,
      "_firstName": "Jessica",
      "_lastName": "Ramos",
      "_email": "apske@fake.com",
      "_phoneNumber": 21997895461
    },
    {
      "_ownerId": 10,
      "_firstName": "Debby",
      "_lastName": "Key",
      "_email": "dat@fake.com",
      "_phoneNumber": 23954495461
    }
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
    },
    {
      "_investmentId": 2,
      "_ownerId": 1,
      "_creationDate": "2022-01-27T08:12:00.000Z",
      "_initialAmount": 179.21,
      "_atualAmount": 179.21,
      "todayExpected": 179.21
    },
    {
      "_investmentId": 3,
      "_ownerId": 1,
      "_creationDate": "2022-01-27T08:12:00.000Z",
      "_initialAmount": 179.21,
      "_atualAmount": 179.21,
      "todayExpected": 179.21
    },
    {
      "_investmentId": 4,
      "_ownerId": 1,
      "_creationDate": "2022-01-27T08:12:00.000Z",
      "_initialAmount": 179.21,
      "_atualAmount": 179.21,
      "todayExpected": 179.21
    },
    {
      "_investmentId": 5,
      "_ownerId": 1,
      "_creationDate": "2022-01-27T08:12:00.000Z",
      "_initialAmount": 179.21,
      "_atualAmount": 179.21,
      "todayExpected": 179.21
    },
    {
      "_investmentId": 6,
      "_ownerId": 1,
      "_creationDate": "2022-01-27T08:12:00.000Z",
      "_initialAmount": 179.21,
      "_atualAmount": 179.21,
      "todayExpected": 179.21
    },
    {
      "_investmentId": 7,
      "_ownerId": 1,
      "_creationDate": "2022-01-27T08:12:00.000Z",
      "_initialAmount": 179.21,
      "_atualAmount": 179.21,
      "todayExpected": 179.21
    },
    {
      "_investmentId": 8,
      "_ownerId": 1,
      "_creationDate": "2022-01-27T08:12:00.000Z",
      "_initialAmount": 179.21,
      "_atualAmount": 179.21,
      "todayExpected": 179.21
    },
    {
      "_investmentId": 9,
      "_ownerId": 1,
      "_creationDate": "2022-01-27T08:12:00.000Z",
      "_initialAmount": 179.21,
      "_atualAmount": 179.21,
      "todayExpected": 179.21
    },
    {
      "_investmentId": 10,
      "_ownerId": 1,
      "_creationDate": "2022-01-27T08:12:00.000Z",
      "_initialAmount": 179.21,
      "_atualAmount": 179.21,
      "todayExpected": 179.21
    },
    {
      "_investmentId": 11,
      "_ownerId": 1,
      "_creationDate": "2022-01-27T08:12:00.000Z",
      "_initialAmount": 179.21,
      "_atualAmount": 179.21,
      "todayExpected": 179.21
    },...
  ]

  


```


