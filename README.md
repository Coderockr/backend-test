# Back End Test
This is a RESTful SOLID API!
## Dependencies
### Docker
Using docker to maximize the compatibility and make the build easyer.
#### docker-compose
Needed to allow multiple services (containers) running. Allows me to escalonate the API and create apps or DB's for it.
### Express
Running the javascript API.

## Ideas
Use React JS and Axios as interface to improve frontend skills, and insert a database for persistence.
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
#### To docker-compose

```bash
docker-compose build
docker-compose up
```

**To stop**
Use the key combination
`CTRL+C`
and then

```bash
docker-compose down
```


__________________________________________________________________________________________________

## Api documentation
The server will run on the adress you specify on the docker-compose file.
It comes for default with:
```yaml
version: '3'
services:
  express:
    build: .
    command: npm start --prefix ./expressapi/
    hostname: "localhost"
    ports:
    - "127.0.0.1:3000:3000"
    volumes:
    - .:/user/app/expressapi

``` 

If you just follow the steps with no editions, the url will be `http://localhost:3000`. Let's assume that for the next examples. To test, I've used **humao.rest-client**. Just install it on **VS CODE** and use the http files to test it as REST api. Soon, there'll be a interface for you :). 

### Endpoints

#### `http://localhost:3000/investment` 
#### `http://localhost:3000/owner` 
#### `http://localhost:3000/withdraw` 
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

### Fail

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


## Owner
### Get all owners
#### Example URI
`GET http://localhost:3000/owner`
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

### Get a owner by ID
#### Example URI
`GET http://localhost:3000/owner/?id={ownerId}`
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
### Get a page of owners
#### Example URI
`GET http://localhost:3000/owner/?page={pageNumber}`
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
## Investment

### Get all investments
#### Example URI
`GET http://localhost:3000/investment`
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

### Get all investments from a user
#### Example URI
`GET http://localhost:3000/investment`
#### Headers 
`Content-Type: application/json`
#### Request
```json
{
  "ownerId":1
}
```
`ownerId = number`

### Get a investment page
#### Example URI
`GET http://localhost:3000/investment/?page={pageNumber}`
#### URI Paramenters
` pageNumber =  number`
#### Headers 
`Content-Type: application/json`

#### Response
```json
  "error": false,
  "message": "Investment page sucessfully loaded",
  "status": 200,
  "objects": [
    {
      "_investmentId": 1,
      "_ownerId": 1,
      "_creationDate": "2001-01-27T08:12:00.000Z",
      "_initialAmount": 179.21,
      "_atualAmount": 179.21,
      "todayExpected": 662.2
    },...
  ]

```



### Get a investment page from a user
#### Example URI
`GET http://localhost:3000/investment/?page={pageNumber}`
#### URI Paramenters
` pageNumber =  number`
#### Headers 
`Content-Type: application/json`
#### Request
```json
{
  "ownerId":1
}
```
`ownerId = number`

#### Response
```json
  "error": false,
  "message": "Owner investment page sucessfully loaded",
  "status": 200,
  "objects": [
    {
      "_investmentId": 1,
      "_ownerId": 1,
      "_creationDate": "2001-01-27T08:12:00.000Z",
      "_initialAmount": 179.21,
      "_atualAmount": 179.21,
      "todayExpected": 662.2
    },...
  ]

```



#### Response
```json
  "error": false,
  "message": "Owner investments sucessfully loaded",
  "status": 200,
  "objects": [
    {
      "_investmentId": 1,
      "_ownerId": 1,
      "_creationDate": "2001-01-27T08:12:00.000Z",
      "_initialAmount": 179.21,
      "_atualAmount": 179.21,
      "todayExpected": 662.2
    },...
  ]

```


### Get a investment by ID
#### Example URI
`GET http://localhost:3000/investment/?id={investmentId}`
#### URI Paramenters
` investmentId =  number`
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

## Withdraw
### Get a withdraw
#### Example URI
`GET http://localhost:3000/withdraw`
#### Headers 
`Content-Type: application/json`
#### Request
```json
{
    "investmentId":1,
    "date":"2010-02-20T08:12Z"
}
```
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
__________________________________________________________________________________________________

## Known errors

### Owner class level erros
##### 01 - Invalid owner first name format
##### 02 - Invalid owner last name format
Only letters are valid for this parameters.


##### 03 - Invalid owner email format
Must follow string@string.string default.

##### 04 - Invalid owner phone number format
Only numbers are allowed on this field.


##### 05 - Invalid parameters format to create an owner
The constructor from the Owner class has received invalid parameters.

### Investment class level errors
##### 06 - Invalid owner id format
The setter of owner id is receiving not numbers or string with only numbers.

##### 07 - Invalid setting of creation date
The setter of creation date is not receiving a valid date (null, NaN, invalid string etc.)

##### 08 - Invalid setting of initial amount
The setter of initial amount is receiving a invalid number. (Comma separated or letters on it.)

##### 09 - Invalid setting of atual amount
The setter of atual amount is receiving a invalid number. (Comma separated or letters on it.)

##### 10 - Invalid investment Age, bad param passed
The setter of investment age are calculating bad dates as it received NaN or past from creation dates.

##### 11 - Unable to withdraw previous withdrawn investment
The function to withdraw detectet that this investment has been already withdrawn.

##### 12 - Cannot withdraw to past from creation date or in future from now
Date stored in creation date is after withdraw date passed.

##### 13 - Invalid parameters format to create an investment
The constructor of investment hasn't been sucessfull to create a valid investment.

### Request level errors

#### GET investment:
##### 14 - Investment not found
The investment id passed is not stored on database.

##### 15 - Invalid investment id
The investment id provided is not according to the defaults. (letter, -1).

##### 16 - No investments for this Owner
There are no investments stored on the database from this owner id. 

##### 17 - Invalid owner id
The owner id provided is not according to the defaults. (letters, -1)

##### 18 - Invalid page number
The page number provided is not according to the defaults. (letters, -1)

##### 19 - No investments found
There are no investments stored on the database at all. 


#### POST investment:
##### 20 - Invalid user id for set an investment
The user id provided is not according to the defaults. (letters, -1)

#### GET owners
##### 21 - Owner not found
The owner id provided is not related to any stored owner.

##### 22 - Invalid owner id
The owner id provided is not according to the defaults. (letters, -1)

##### 23 - No owners found
There are no owners stored on the database at all. 

#### GET withdraw
##### 24 - Investment not found
The investment id provided is not related to any stored owner.

##### 25 - Invalid date to Withdraw
The date provided is not according to the defaults. ("YYYY-MM-DDTHH:MMT")

Examples:
`"2011-02-20T05:12Z"`
`"2021-06-15T08:12-0300"`
`"2023-01-18T00:12-0100"`
`"2015-04-09T23:12+0400"`



