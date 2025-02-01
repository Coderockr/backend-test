# Coderockr Backend Test

A submission for Coderockr's backend development test.

## Description

An investment management application exposed through a REST API that deals with 3 entities:

- Investors
- Investments
- Withdrawals

The system was programmed in Go, and uses the default library's http server.

It has the following features:

- HTTP endpoints for creating and retrieving the entities mentioned above.
- An interest applying functionality that works on a timely basis implemented through MySQL's event scheduler.
- Unit tests implemented via dependency injection
- A low number of third party libraries (i.e. github.com/go-sql-driver/mysql and github.com/go-playground/validator/v10)
- Somewhat thorough error handling, which allows for informative error messages to the end-user.

## Requirements

In order to run the application, you'll need the following:

- Go
- Docker

Also, to view the documentation you'll need Postman.

## Run Locally

1. Go to the project directory

```bash
  cd backend-test
```

2. Start the database

```bash
  docker compose up -d
```

3. Start the server (Available at port 8080 by default)

```bash
  go run main.go
```

## API Documentation

I have not served the API documentation via http due to time constraints.

Instead, I documented the API in Postman and exported the result to the coderockr_backend_test.postman_collection.json file which is located in the projects's root.

To use it you'll have to import it in Postman.

I apologize for the inconveninence.

## Closing Remarks

I'd like to point out that this projects still lack MANY features to even be considered a first prototype. Stuff like:

- Authentication
- Endpoints for updating and deleting entities
- Email notifications

Although I have developed REST APIs for some of my personal projects, I had yet to tackle this matter in such a intricate manner, and so it took quite a considerable amount of time for me to finally be able to show you something.

I'd like to thank the Coderockr team for the opportunity. This was definitely a great learning experience for me.

Wish you guys a good one.

Take care!
