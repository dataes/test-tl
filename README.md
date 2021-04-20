# Technical Assessment
Problem 1 : Discounts
We need you to build us a small (micro)service that calculates discounts for orders.

How discounts work
For now, there are three possible ways of getting a discount:

A customer who has already bought for over € 1000, gets a discount of 10% on the whole order.
For every product of category "Switches" (id 2), when you buy five, you get a sixth for free.
If you buy two or more products of category "Tools" (id 1), you get a 20% discount on the cheapest product.
By the way: there may become more ways of granting customers discounts in the future.

APIs
In the example-orders directory, you can find a couple of example orders. We would like to send them to your service in this form. How the discounts are returned, is up to you. But make sure the reasons for the discounts are transparent.

In the data directory, you can find source files for customer data and product data. You can assume these are in the format of the real external API.

Guidelines
You are free to use any framework and packages that you like.

Teamleader is quite a big application, with many developers working on the code at the same time. It is no surprise that because of this, maintainability is one of the core values of the engineering team. Keep this in mind while working on your solution.

# Problem 1 : Discounts

We need you to build us a small (micro)service that calculates discounts for orders.

## How discounts work

For now, there are three possible ways of getting a discount:

- A customer who has already bought for over € 1000, gets a discount of 10% on the whole order.
- For every product of category "Switches" (id 2), when you buy five, you get a sixth for free.
- If you buy two or more products of category "Tools" (id 1), you get a 20% discount on the cheapest product.

By the way: there may become more ways of granting customers discounts in the future.

-------------------------------------------------

# REST API IN SLIM PHP

Example of RESTful API microservices with [Slim PHP micro framework](https://www.slimframework.com).

Main technologies used: `PHP 7, Slim 3, MySQL, Redis, dotenv, PHPUnit and JSON Web Tokens.`

## :gear: QUICK INSTALL:

### Requirements:

- Git.
- Composer.
- PHP 7.4+.
- MySQL/MariaDB.
- Redis (Optional).
- or Docker.

### With Git:

In your terminal execute this commands:

```bash
$ git clone https://github.com/dataes/test-tl.git && cd test-tl
$ cp .env.example .env
$ composer install
$ composer restart-db
$ composer test
$ composer start
```


### With Docker:

You can use this project using **docker** and **docker-compose**.


**Minimal Docker Version:**

* Engine: 18.03+
* Compose: 1.21+


**Commands:**

```bash
# Start the API (this is my alias for: docker-compose up -d --build).
$ make up

# To create the database and import test data from scratch.
$ make db

# Checkout the API.
$ curl http://localhost:8081

# To go in the php container;
$ make php

# To run the tests.
$ composer test

# Stop and remove containers (it's like: docker-compose down).
$ make down
```

## :package: DEPENDENCIES:

### LIST OF REQUIRE DEPENDENCIES:

- [slim/slim](https://github.com/slimphp/Slim): Slim is a PHP micro framework that helps you quickly write simple yet powerful web applications and APIs.
- [respect/validation](https://github.com/Respect/Validation): The most awesome validation engine ever created for PHP.
- [palanik/corsslim](https://github.com/palanik/CorsSlim): Cross-origin resource sharing (CORS) middleware for PHP Slim.
- [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv): Loads environment variables from `.env` to `getenv()`, `$_ENV` and `$_SERVER` automagically.
- [predis/predis](https://github.com/nrk/predis/): Flexible and feature-complete Redis client for PHP and HHVM.
- [firebase/php-jwt](https://github.com/firebase/php-jwt): A simple library to encode and decode JSON Web Tokens (JWT) in PHP.

### LIST OF DEVELOPMENT DEPENDENCIES:

- [phpunit/phpunit](https://github.com/sebastianbergmann/phpunit): The PHP Unit Testing framework.
- [phpstan/phpstan](https://github.com/phpstan/phpstan): PHPStan - PHP Static Analysis Tool.
- [pestphp/pest](https://github.com/pestphp/pest): Pest is an elegant PHP Testing Framework with a focus on simplicity.
- [nunomaduro/phpinsights](https://github.com/nunomaduro/phpinsights): Instant PHP quality checks from your console.
- [vimeo/psalm](https://github.com/vimeo/psalm): A static analysis tool for finding errors in PHP applications.


## :traffic_light: TESTING:

Run all PHPUnit (integration) tests with `composer test`.

```bash
$ composer test
> phpunit
PHPUnit 9.5.1 by Sebastian Bergmann and contributors.

........................................................          63 / 63 (100%)

Time: 00:01.459, Memory: 18.00 MB

OK (63 tests, 403 assertions)
```


## :books: DOCUMENTATION:

### ENDPOINTS:

#### INFO:

- Help: `GET /`

- Status: `GET /status`


#### USERS:

- Login User: `POST /login`

- Create User: `POST /api/v1/users`

- Update User: `PUT /api/v1/users/{id}`

- Delete User: `DELETE /api/v1/users/{id}`


#### PRODUCTS:

- Get All Products: `GET /api/v1/products`

- Get One Product: `GET /api/v1/products/{id}`

- Create Product: `POST /api/v1/products`

- Update Product: `PUT /api/v1/products/{id}`

- Delete Product: `DELETE /api/v1/products/{id}`


#### ORDERS:

- Get your Orders: `GET /api/v1/orders`

- Get One of your Order: `GET /api/v1/orders/{id}`

- Create Order: `POST /api/v1/orders`

- (TODO) Update Note: `PUT /api/v1/orders/{id}`

- Delete Order: `DELETE /api/v1/orders/{id}`

--

( note : I have decided to skip a lot of things due to a miss of time and because it's a test 
I assume the goal is to see how I would implement that. 
That's why I have created a TODO list )

### ------------------------!! TODO LIST !!------------------------
- Do UNIT TESTS with mocks for entities and service logic (was supposed to be TDD)
- Add MORE tests and try to break the app
- Test coverage should be 100% covered
- Do fixtures
- Instead of using directly the repositories we should create some repositoryInterface, then the services will use the interface, and the repository will implement the interface in order to solidify it as a contract
- Add 'revenue' in users table and subtraction logic + exception if no revenues on orders
- Create REST api for "category" like product and order
- Order : update
- Add quantity as a property in Product + logic to subtract quantity when ordering
- Add Discount table with discount type enumeration in order to know what order/user got what discounts  
- Check if category exist on Product operations
- Create multiple products from an array instead of single one
- Delete related date when we delete a user
- User faker in unit tests
- Create a Postman collection ready to use
- Use swagger and create a better documentation for api endpoints
- More stats in getDbStats()
- I assume that I can not have multiple time the same product-id from the request, do the validation regarding that  
- Do a better validation if possible
- Refactoring if possible

## :page_facing_up: LICENSE

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.


[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat
