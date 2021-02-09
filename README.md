# Back End Test Project

API for event social networking applications.

## Features

### __Public Area:__

1. A public event list: When accessing the main route, the application should show a list of all the events registered, paging them every 10 events;
- he main route is http://localhost:3000/events/1

1.1 The user should be able to filter the list of events by dates, or regions;
- Routes for filter by region: http://localhost:3000/event/region/nameRegion
- Routes for filter by date: http://localhost:3000/event/date/dateWithTimezone

2. Event details: the application must allow the user to see the details of the event, by clicking on the event listing, or accessing the event link;
- Route for event details: http://localhost:3000/event/index/idEvent

3. User signup: the application should allow the user to register by informing: Name, Email, Password, Bio, Profile Picture, City, and State;
- Route for user signup: http://localhost:3000/user/create
- Requirements: Name:String, Email: String, Password: String, Bio: String, Profile_picture: file, City: String, State: String on body request.

4. User login: The application should allow the user to login using their credentials;
- Route for login: http://localhost:3000/user/auth
- The user must provide email and password on body request.

## Usage

To run the app make a clone of this repository, change to branch development and install the dependencies run on terminal:

``npm install``

Run the app:

``npm run dev``

The server init on port 3000. Acess: http://localhost:3000/index/1


## Libs

- [Sequelize](https://sequelize.org/master/) A ORM that make solid transaction support, relations, eager and lazy loading, read replication and more
- [Multer](https://github.com/expressjs/multer#readme): Used for upload images
- [JWT](https://github.com/auth0/node-jsonwebtoken): Used to create a token for users
- [Dotenv](https://github.com/motdotla/dotenv): Loads environment variables from a .env file
- [Sequelize-Cli](https://github.com/sequelize/cli): The sequelize CLI helps to create tables, migrations and seeders
- [Sequelize-paginate](https://github.com/eclass/sequelize-paginate#readme): Plugin for add paginate method
- [Cors](https://github.com/expressjs/cors): Used enable cors for some routes
- [ Nodemon](https://github.com/remy/nodemon): Helps develop node.js based applications by automatically restarting the node application when file changes in the directory are detected
- Pg, pg-hstore: Libs to support Postgres database.
