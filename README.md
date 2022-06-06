# Backend Test

## What is this
 its a investments app backend to create investments, control monthly gains (based on taxes) and withdrawal those investments.

## Technologies and tools
Recently i'm enjoying to use this stack and tools mostly because of the easy setup and his semantical way to do things. So its a express server with graphql, using Apollo as server. Then we have *type-graphql* to handle graphql stuff with classes and use decorators (aka reflection-metadata). As database i choose mongodb because of the simple entities/relations setup and his lib (typegoose) works well with type-graphql. For tests i use Jest with supertest to simulate the graphql server.

## How to Execute

First of all you will need to install [git](https://git-scm.com/downloads), then [nodejs](https://nodejs.org/en/download/) and [docker](https://docs.docker.com/desktop/windows/install/) to run the server. With that, clone this repository. After open your terminal in the folder that you clone the repo, run ***npm install*** to install all dependencies. Finally to run the server and database type the command ***docker-compose up --build*** to up the docker container (if the command not work maybe you will have to [add docker in windows enviroment variables](https://stackoverflow.com/questions/49478343/windows-doesnt-recognize-docker-command)). Now the server should be running, open *http://localhost:3333/graphql* to see the magic.

## Documentation
Apollo Graphql for itself has a documentation in his playground, showing all the queries, mutations and so on. To see the docs its just open the button *DOCS* on the right side of the playground.

## Tests
To run the integration-tests, you will need to run the database container ***docker-compose up --build*** and run the command ***yarn test***.

## Final Considerations
I Enjoyed to do this test since i tried tools and stack that i like but don't use on normal days, so
was a great experience, put me out of comfort zone, read some docs and try to make things work.
>>>>>>> development
