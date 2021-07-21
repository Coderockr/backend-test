# Back End Test Project <img src="https://coderockr.com/assets/images/coderockr.svg" align="right" height="50px" />

You should see this challenge as an opportunity to create an application following modern development best practices (given the stack of your choice), but also feel free to use your own architecture preferences (coding standards, code organization, third-party libraries, etc). It’s perfectly fine to use vanilla code or any framework or libraries.

## Scope

In this challenge you should build an API for an application that stores and manages investments, it should have the following features:

  1. __Creation__ of an investment with an owner, a creation date and an amount.
    1. The creation date of a investment can be today or an date in the past.
    2. An investment should not be or become negative.
  2. __View__ of an investment with its withdrawals and current balance.
    1. Current balance should be the sum of the invested amount, the [interest] and withdraws.
  3. Partial or total __withdraw__ of the investment amount.
    1. Should not be able to withdraw more than the available balance.
    2. Withdraws in the past can be created, but can't happen before the investment creation or previously created withdrawals.
    3. Should show the amount paid on [taxes].
  4. __List__ of a person's investments
    1. This list should have pagination.

__NOTE:__ the implementation of an interface will not be evaluated.

### Interest Calculation

The investment will pay 0.52% every month in the same day of the investment creation, if a withdraw is made then in that month no interest will be paid.

Given that the interest is paid every month, it should be treated as as compound interest, which means that interest that is not withdrawn will become part of the investment balance for the next interest payment.

### Taxation

Every time a withdrawal happens part of it will automatically be kept as taxes.
The taxes apply only to the interest, meaning that if the investment was created with 1000.00, and now the balance is 1,100.00, if you withdraw 110.00 than the taxes will be calculated over 11.00 (10% = 1,100/1000).

The tax percentage changes according to the age of the investment:
* If it is less than one year old, the percentage will be 22.5%
* If it is between one and two years old, the percentage will be 18.5%.
* If older than two years, the percentage will be 15%.

## Requirements
1. Create project using any technology of your preference. It’s perfectly OK to use vanilla code or any framework or libraries;
2. Although you can use as many dependencies as you want, you should manage them wisely;
3. The API should be covered by unit tests;
4. It is not necessary to send the notification emails, however, the code required for that would be welcome;
5. The API must be documented in some way.

## Deliverables
The project source code and dependencies should be made available in GitHub. Here are the steps you should follow:
1. Fork this repository to your GitHub account (create an account if you don't have one, you will need it working with us).
2. Create a "development" branch and commit the code to it. Do not push the code to the main branch.
3. Include a README file that describes:
    - Special build instructions, if any
    - List of third-party libraries used and short description of why/how they were used
    - A link to the API documentation.
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
- This challenge description is intentionally vague in some aspects, but if you need assistance feel free to ask for help.
- If any of the seens out of your current level, you may skip it, but remember to tell us about it in the pull request.

## Credits

This coding challenge was inspired on [kinvoapp/kinvo-back-end-test](https://github.com/kinvoapp/kinvo-back-end-test/blob/2f17d713de739e309d17a1a74a82c3fd0e66d128/README.md)

[taxes]: #taxation
[interest]: #interest-calculation
