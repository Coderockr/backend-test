# Back End Test Project <img src="https://coderockr.com/assets/images/coderockr.svg" align="right" height="50px" />

https://mailtrap.io/inboxes/1766647/messages/2812274287

Login

Create inbox

Select Laravel 7+

Copy the keys

Replace the variables in the .env file, using the new ones.

BASE_URL = http://127.0.0.1:8000/api/v1/

[POST] BASE_URL/register

    Description: Creates a user account and assign its a token to do the other program actions.

    Headers: Accept: application/json

    Body Parameters:

        * name (required) (string)

        * email (required) (string) (unique)

        * password (required) (string) (must match with password_confirmation)

        * password_confirmation (required) (string) (must match with password_confirmation)

    Status:

        * (201) Account created

        * (422) Validation body parameters error


[POST] BASE_URL/logout
    Description: Logout the user and delete its token.
    Headers: Accept: application/json
    Authorization: Bearer Token
    Status:
        200 - Logged out
        401 - Unauthorized

[POST] BASE_URL/login
    Description: Login a user in the program.
    Headers: Accept: application/json
    Body Parameters:
        - email (required) (string)
        - password (required) (string) (must match with password_confirmation)
    Status:
        201 - Logged in
        401 - Unauthorized
        422 - Validation body parameters error      

[POST] BASE_URL/investments
    Description: Create a investment assigned to the user.
    Headers: Accept: application/json
    Authorization: Bearer Token
    Body Parameters:
        - amount (required) (numeric) (between: 0 - 999999.99) (regex:/^-?[0-9]+(?:\.[0-9]{1,2})?$/)
        - inserted_at (required) (date) (before_or_equal: today) (date_format: Y-m-d)
    Status:
        201 - Investment created
        302 - Validation body parameters error
        401 - Unauthorized

[GET] BASE_URL/investments
    Description: Get a paginated list with all the user investment.
    Headers: Accept: application/json
    Authorization: Bearer Token
    Status:
        200 - Ok
        401 - Unauthorized

[GET] BASE_URL/investments/{id}
    Description: Show the informations (amount and expected balance) about the investiment with the id passed in the url.
    Headers: Accept: application/json
    Authorization: Bearer Token
    Query params:
        - id (required) (integer)
    Status:
        200 - Ok
        401 - Unauthorized
        404 - Not Found

[POST] BASE_URL/investments/{id}/withdrawal
    Description: Withdrawal an investment with the id passed in the url.
    Headers: Accept: application/json
    Authorization: Bearer Token
    Query params:
        - id (required) (integer)
    Status:
        201 - Successfull investment withdrawal
        401 - Unauthorized
        404 - Not Found
