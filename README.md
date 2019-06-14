# Back End Test Project
You should see this challenge as an opportunity to create an application following modern development best practices (given the stack of your choice), but also feel free to use your own architecture preferences (coding standards, code organization, third-party libraries, etc). It’s perfectly fine to use vanilla code or any framework or libraries.

## Scope
In this challenge you should build an API for an application such as a social event network that implements the following features:

__NOTE:__ the implementation of an interface will not be evaluated.

### Public Area
1. __A public event list:__ When accessing the main route, the application should show a list of all the events registered, paging them every 10 events;
- 1.1 The user should be able to filter the list of events by dates, or regions;
2. __Event details:__ the application must allow the user to see the details of the event, by clicking on the event listing, or accessing the event link;
3. __User signup:__ the application should allow the user to register by informing: Name, Email, Password, Bio, Profile Picture, City, and State;
4. __User login:__ The application should allow the user to login using their credentials;
- 4.1 The login should persist when the application is closed, or reloaded;

### Logged Area
5. __Friend invitation:__ the application will allow the user to enter an email to add as a friend;
6. __Add as friend:__ The informed user should receive a friend request, or an invitation to register, if they are not already a user;
7. __Friendship management:__ the user will be able to see your new friend requests, list your friends, and undo friendships;
8. __Event registration:__ the application should allow the user to register an event by informing: Name, Description, Date, Time, and Place;
- 8.1 The user should be able to edit and cancel events their events;
9. __Invite friends to event:__ the user can invite their friends to events, being able to invite all friends, or only the selected ones;
- 9.1 If the user has already been invited to the event, regardless of their status (confirmed, rejected, awaiting confirmation), the invited user should not be notified of the invitation again;
10. __My event list:__ the user should be able to see their events, being able to filter them by those who will participate, and the ones that he created;
11. __Manage event invitations:__ The user can accept, or reject, attend events.
12. __Events management:__ The user can view their rejected events and undo rejections, deciding to participate, if the event has not yet occurred;

## Requirements
1. Create project using any technology of your preference. It’s perfectly OK to use vanilla code or any framework or libraries;
2. Although you can use as many dependencies as you want, you should manage them wisely;
3. The API should be covered by unit tests;
4. It is not necessary to send the notification emails, however, the code required for that would be welcome;
5. The API must be documented in some way.

## Deliverables
The project source code and dependencies should be made available in GitHub. Here are the steps you should follow:
1. Create a public repository on GitHub (create an account if you don't have one).
2. Create a "development" branch and commit the code to it. Do not push the code to the master branch.
3. Include a README file that describes:
  - Special build instructions, if any
  - List of third-party libraries used and short description of why/how they were used
4. Once the work is complete, create a pull request from "development" into "master" and send us the link.
5. Avoid using huge commits hiding your progress. Feel free to work on a branch and use rebase to adjust your commits before submitting the final version.

## Coding Standards
When working on the project be as clean and consistent as possible.

## Project Deadline
Ideally you'd finish the test project in 5 days. It shouldn't take you longer than a entire week.

## Quality Assurance
Use the following checklist to ensure high quality of the project.

### General
- First of all, the application should run without errors.
- Are all requirements set above met?
- Is coding style consistent?
- The API is well documented?

## Submission
1. A link to the Github repository.
2. Briefly describe how you decided on the tools that you used.

## Have Fun Coding 🤘
This challenge description is intentionally vague in some aspects, but if you need assistance feel free to ask for help.
