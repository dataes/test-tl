# Technical Assessment

https://github.com/teamleadercrm/coding-test/blob/master/1-discounts.md

-------------------------------------------------

# REST API IN SLIM PHP

Example of RESTful API microservices with [Slim PHP micro framework](https://www.slimframework.com).

Main technologies used: `PHP 7, Slim 3, MySQL, Redis, dotenv, PHPUnit and JSON Web Tokens.`

Also, There are additional tools like: `Docker & Docker Compose, Travis CI, Code Climate, Scrutinizer, Sonar Cloud, PHPStan, PHP Insights, Heroku and CORS.`

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

Run all PHPUnit tests with `composer test`.

```bash
$ composer test
> phpunit
PHPUnit 9.5.1 by Sebastian Bergmann and contributors.

........................................................          57 / 57 (100%)

Time: 00:01.378, Memory: 16.00 MB

OK (57 tests, 328 assertions)
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


### ------------------------!! TODO LIST !!------------------------
- Add 'revenue' in users table and subtraction logic + exception if no revenues on orders
- Create REST api for "category" like product and order
- Order : update
- Add quantity as a property in Product + logic to subtract quantity when ordering
- Join Products linked to an order for the get request
- Instead of using directly the repositories we should create some repositoryInterface, then the base services will use the interface, and the repository will implement the interface in order to solidify it as a contract
- Check if category exist on Product operations
- Create multiple products from an array instead of single one
- Delete related date when we delete a user
- User faker in unit tests
- Do Unit Tests for entities and service logic
- Add more tests and try to break the applicaton
- Test coverage should be 100% covered
- create a Postman collection ready to use
- use swagger and create a better documentation for api

## :page_facing_up: LICENSE

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.


[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat
