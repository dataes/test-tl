
# Rest Api Slim PHP
This simple RESTful API made in Slim version 3, allows CRUD operations to manage entities like: Users, Products and Orders :-)


## Indices

* [Info](#info)

  * [Get Help](#1-get-help)
  * [Get Status](#2-get-status)

* [Login](#login)

  * [Login](#1-login)

* [Orders](#orders)

  * [Get All Orders](#1-get-all-orders)
  * [Get One Order](#2-get-one-order)
  * [Create Order](#3-create-order)
  * [Update Order](#4-update-order)
  * [Delete Order](#5-delete-order)

* [Products](#products)

  * [Get All Products](#1-get-all-products)
  * [Get One Product](#2-get-one-product)
  * [Create Product](#3-create-product)
  * [Update Product](#4-update-product)
  * [Delete Product](#5-delete-product)

* [Users](#users)

  * [Get All Users](#1-get-all-users)
  * [Get One User](#2-get-one-user)
  * [Create User](#3-create-user)
  * [Update User](#4-update-user)
  * [Delete User](#5-delete-user)


--------


## Info
Get information about API.



### 1. Get Help


Get help about this api.


***Endpoint:***

```bash
Method: GET
Type: 
URL: {{app}}
```



***Responses:***


Status: Get Help | Code: 200



```js
{
    "code": 200,
    "status": "success",
    "message": {
        "endpoints": {
            "products": "http://localhost:8080/api/v1/products",
            "users": "http://localhost:8080/api/v1/users",
            "orders": "http://localhost:8080/api/v1/orders",
            "status": "http://localhost:8080/status",
            "this help": "http://localhost:8080"
        },
        "version": "0.22.2",
        "timestamp": 1560897542
    }
}
```



### 2. Get Status


Get status of this api.


***Endpoint:***

```bash
Method: GET
Type: 
URL: {{app}}/status
```



***Responses:***


Status: Get Status | Code: 200



```js
{
    "code": 200,
    "status": "success",
    "message": {
        "db": {
            "users": 9,
            "products": 10,
            "orders": 5
        },
        "version": "0.22.2",
        "timestamp": 1560897579
    }
}
```



## Login



### 1. Login


Login and get a JWT Token Authorization Bearer to use this api.


***Endpoint:***

```bash
Method: POST
Type: RAW
URL: {{app}}/login
```


***Headers:***

| Key | Value | Description |
| --- | ------|-------------|
| Content-Type | application/json |  |



***Body:***

```js        
{
    "email": "super.email@host.com",
    "password": "OnePass1"
}
```



***Responses:***


Status: Login Failed | Code: 400



```js
{
    "message": "Login failed: Email or password incorrect.",
    "class": "UserException",
    "status": "error",
    "code": 400
}
```



Status: Login OK | Code: 200



```js
{
    "code": 200,
    "status": "success",
    "message": {
        "Authorization": "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIxMSIsImVtYWlsIjoibUBiLmNvbS5hciIsIm5hbWUiOiJNTkIiLCJpYXQiOjE1NTg1NTMwNTIsImV4cCI6MTU1OTE1Nzg1Mn0.OQyICWlGW0oSUB-ANrYL2OJTdC2v0OQQO3RQQ3W_KLo"
    }
}
```



## Orders
Manage Orders.



### 1. Get All Orders



***Endpoint:***

```bash
Method: GET
Type: 
URL: {{app}}/api/v1/orders
```



***Query params:***

| Key | Value | Description |
| --- | ------|-------------|
| page | 1 |  |
| name |  |  |
| description |  |  |
| perPage | 10 |  |



### 2. Get One Order



***Endpoint:***

```bash
Method: GET
Type: 
URL: {{app}}/api/v1/orders/3
```



### 3. Create Order


***Endpoint:***

```bash
Method: POST
Type: RAW
URL: {{app}}/api/v1/orders
```


***Headers:***

| Key | Value | Description |
| --- | ------|-------------|
| Content-Type | application/json |  |



***Body:***

```js        
{
  "id": "1",
  "customer-id": "1",
  "items": [
    {
      "product-id": "A101",
      "quantity": "2",
      "unit-price": "9.75",
      "total": "19.50"
    },
    {
      "product-id": "A102",
      "quantity": "1",
      "unit-price": "49.50",
      "total": "49.50"
    }
  ],
          "total": "69.00"
}
```



### 4. Update Order (TODO)



### 5. Delete Order



***Endpoint:***

```bash
Method: DELETE
Type: FORMDATA
URL: {{app}}/api/v1/orders/1
```



## Products
Manage Products.



### 1. Get All Products


Get all products of a user.


***Endpoint:***

```bash
Method: GET
Type: 
URL: {{app}}/api/v1/products
```


***Headers:***

| Key | Value | Description |
| --- | ------|-------------|
| Authorization | {{jwt}} |  |



***Query params:***

| Key | Value | Description |
| --- | ------|-------------|
| page | 1 |  |
| perPage | 5 |  |
| name |  |  |
| description |  |  |
| status |  |  |



### 2. Get One Product


Get one product of a user.


***Endpoint:***

```bash
Method: GET
Type: 
URL: {{app}}/api/v1/products/13
```


***Headers:***

| Key | Value | Description |
| --- | ------|-------------|
| Authorization | {{jwt}} |  |



### 3. Create Product


Create a product.


***Endpoint:***

```bash
Method: POST
Type: RAW
URL: {{app}}/api/v1/products
```


***Headers:***

| Key | Value | Description |
| --- | ------|-------------|
| Content-Type | application/json |  |
| Authorization | {{jwt}} |  |



***Body:***

```js        
{
  "id":"A101",
  "description": "Screwdriver",
  "category": "1",
  "price": "99.99"
}

```



### 4. Update Product


Update a product of a user.


***Endpoint:***

```bash
Method: PUT
Type: RAW
URL: {{app}}/api/v1/products/29
```


***Headers:***

| Key | Value | Description |
| --- | ------|-------------|
| Content-Type | application/json |  |
| Authorization | {{jwt}} |  |



***Body:***

```js        
{
  "description": "Screwdriver2",
  "category": "2",
  "price": "99.99"
}

```



### 5. Delete Product


Delete a product of a user.


***Endpoint:***

```bash
Method: DELETE
Type: FORMDATA
URL: {{app}}/api/v1/products/Screwdriver2
```


***Headers:***

| Key | Value | Description |
| --- | ------|-------------|
| Authorization | {{jwt}} |  |



## Users
Manage Users.



### 1. Get All Users



***Endpoint:***

```bash
Method: GET
Type: 
URL: {{app}}/api/v1/users
```


***Headers:***

| Key | Value | Description |
| --- | ------|-------------|
| Authorization | {{jwt}} |  |



***Query params:***

| Key | Value | Description |
| --- | ------|-------------|
| page | 1 |  |
| name |  |  |
| email |  |  |
| perPage | 10 |  |



### 2. Get One User



***Endpoint:***

```bash
Method: GET
Type: 
URL: {{app}}/api/v1/users/8
```


***Headers:***

| Key | Value | Description |
| --- | ------|-------------|
| Authorization | {{jwt}} |  |



### 3. Create User


Register a new user.


***Endpoint:***

```bash
Method: POST
Type: RAW
URL: {{app}}/api/v1/users
```


***Headers:***

| Key | Value | Description |
| --- | ------|-------------|
| Content-Type | application/json |  |



***Body:***

```js        
{
  "name": "John User",
  "email": "super.email@host.com",
  "password": "OnePass1"
}
```



### 4. Update User


Update a user.


***Endpoint:***

```bash
Method: PUT
Type: RAW
URL: {{app}}/api/v1/users/9
```


***Headers:***

| Key | Value | Description |
| --- | ------|-------------|
| Content-Type | application/json |  |
| Authorization | {{jwt}} |  |



***Body:***

```js        
{
  "name": "John The User 22",
  "email": "super.email@host.com",
  "password": "OnePass1"
}
```



### 5. Delete User


Delete a user.


***Endpoint:***

```bash
Method: DELETE
Type: FORMDATA
URL: {{app}}/api/v1/users/112
```


***Headers:***

| Key | Value | Description |
| --- | ------|-------------|
| Authorization | {{jwt}} |  |
