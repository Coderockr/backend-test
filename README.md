# API investments

[![typescript](https://img.shields.io/badge/typescript-4.3.5-3178c6?style=flat-square&logo=typescript)](https://www.typescriptlang.org/)
[![postgres](https://img.shields.io/badge/postgres-8.6.0-326690?style=flat-square&logo=postgresql&logoColor=white)](https://www.postgresql.org/)
[![jest](https://img.shields.io/badge/jest-27.0.6-brightgreen?style=flat-square&logo=jest)](https://jestjs.io/)
<br>

[![Run in Insomnia}](https://insomnia.rest/images/run.svg)](https://insomnia.rest/run/?label=Rentx&uri=https%3A%2F%2Fgithub.com%2FDaniel-Vinicius%2Frentx%2Fblob%2Fmain%2Fdocs%2Finsomnia.json)

API for an application that stores and manages investments.

---

### Installing as dependencies

```
$ yarn
```
Or:
```
$ npm install
```
---

### **Configuring database**
The Application uses database: [Postgres](https://www.postgresql.org/). For faster setup it is recommended to use [docker-compose](https://docs.docker.com/compose/), you just need to up all the services:
```
$ docker-compose up
```
### Migrations
To run migrations:
```
$ yarn migrate
```
### **Running the tests**
[Jest](https://jestjs.io/) was used to do the tests, to run:
```
$ yarn test
```
Or:
```
$ npm run test
```


### **Coverage report**
You can see the coverage report inside `coverage`. It is created automatically after running the tests.