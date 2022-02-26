# Back End Test
This is a RESTful SOLID API!
## Libraries used
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

To stop the execution :
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
      "_email": "email@fake.com",
      "_phoneNumber": 11963258874
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
`GET http://THEADDRESSYOUWISH/?id={investor_id}`
#### URI Paramenters
` investor_id number`
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
      "_firstName": "Lucas",
      "_lastName": "Nascimento",
      "_email": "email@fake.com",
      "_phoneNumber": 11963258874
    }
  ]
```


