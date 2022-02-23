# Back End Test Project CODEROCKR

## libraries used
### Lumen
just Lumen, which is a micro php framework derived from Laravel.
I really like the structure of Laravel and its well done documentation. Since the framework has a lot of ready-made stuff that speeds up the work, such as the connection to the database, and the ORM, as well as configurations for tests.
## how to build
to terminal run the command below
```bash
  docker-compose up -d
```
with the containers running, access the php container and install the composer dependencies

```bash
  docker exec -it investment_portfolio_app bash
```
```bash
  composer install
```
if you need, permission in the log directory that is inside the storage

```bash
  chmod -R 777 storage/log
```
## api documentation
the server will run on `http://localhost:8000` 

### endpoints


---
*** investor ***


![](https://img.shields.io/badge/post-green?style=for-the-badge)
 
---

**Investor**
----
  .

* **URL**

  /users/:id

* **Method:**

  `GET`
  
*  **URL Params**

   **Required:**
 
   `id=[integer]`

* **Data Params**

  None

* **Success Response:**

  * **Code:** 200 <br />
    **Content:** `{ id : 12, name : "Michael Bloom" }`
 
* **Error Response:**

  * **Code:** 404 NOT FOUND <br />
    **Content:** `{ error : "User doesn't exist" }`

  OR

  * **Code:** 401 UNAUTHORIZED <br />
    **Content:** `{ error : "You are unauthorized to make this request." }`

* **Sample Call:**

  ```javascript
    $.ajax({
      url: "/users/1",
      dataType: "json",
      type : "GET",
      success : function(r) {
        console.log(r);
      }
    });
  ```
