# API investments

[![typescript](https://img.shields.io/badge/typescript-4.3.5-3178c6?style=flat-square&logo=typescript)](https://www.typescriptlang.org/)
[![postgres](https://img.shields.io/badge/postgres-8.6.0-326690?style=flat-square&logo=postgresql&logoColor=white)](https://www.postgresql.org/)
[![jest](https://img.shields.io/badge/jest-27.0.6-brightgreen?style=flat-square&logo=jest)](https://jestjs.io/)
<br>

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

### **Running application**
The Application uses database: [Postgres](https://www.postgresql.org/). For faster setup it is recommended to use [docker-compose](https://docs.docker.com/compose/), you just need to up all the services:
```
$ docker-compose up
```
* The URL to access the API is http://localhost:3333/

* The documentation was made using the swagger library to access it open your browser and access this address http://localhost:3333/api-docs in this URL are all the routes of the application.

* In the root folder of the project there is a .png file of the Database entity relationship diagram, the diagram.png file

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
### **List of used libraries**
### *List of used libraries production*

* prisma/client: Toolkit ORM for database of application for production;
* bcrypt: Password encryption. Used when comparing passwords coming from request with the password      saved in the database table;
* dayjs: Manipulation and calculation of dates;
* express: JS node framework;
* express-async-errors: Viewing application errors;
* jsonwebtoken: Authentication and hash token generation;
* reflect-metadata: Library required by prisma;
* swagger-ui-express: Library for API documentation;
* tsyringe: Library for dependency injection;
* uuid: Library to generate records id.

### *List of used libraries development*
* jest: Library for application unit testin;
* prisma: Toolkit ORM for database of application for development;
* ts-jest: Library for application unit testing in applications using TypeScript;
* ts-node-dev: Library to reload the application when making code changes;
* tsconfig-paths: Map application directories;
* typescript: Library to allow the use of TypeScript in the application.


### **Coverage report**
You can see the coverage report inside `coverage`. It is created automatically after running the tests.