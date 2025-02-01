# Coderockr Backend Test

A submission for Coderockr's backend development test.

## Description

An investment managment application exposed through a REST API that deals with 3 entities:

- Investors
- Investments
- Withdrawals

The system was programmed in Go, and uses the default library's http server.

Unit tests were implemented for the data access layer and for the requisition handlers, which was made possible through dependency injection.

It uses 2 third-party packages:

- github.com/go-sql-driver/mysql
- github.com/go-playground/validator/v10

There's a setup.sql file in the project's root. It is used in the database container to create the relevant tables and a scheduled event that applies the investments' interest monthly.

## Requirements

In order to run the application, you'll need the following:

- Go
- Docker

Also, to view the documentation you'll need Postman.

## Run Locally

Go to the project directory

```bash
  cd backend-test
```

Start the database

```bash
  docker compose up -d
```

Start the server (Available at port 8080 by default)

```bash
  go run main.go
```

## API Documentation

I have not served the API documentation via http due to time constraints.

Instead, I documented the API in Postman and exported the result to the coderockr_backend_test.postman_collection.json file which is located in the projects's root.

To use it you'll have to import it in Postman.

I apologize for the inconveninence.

## Closing Remarks

I'd like to thank the Coderockr team for the opportunity, this was definitely a great learning experience.

Wish you guys the best.

Take care!
