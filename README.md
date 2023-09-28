# Cribbage Coach

A web application to help decide which cards to discard during cribbage play. Based on [Michael Schell's discard tables.](http://cribbageforum.com/SchellDiscard.htm)

### Requirements

- [Docker](https://docs.docker.com/get-docker/)
- Or [PHP 7.x](https://www.php.net/) and [Composer](https://getcomposer.org/download/)

### How to Use

- Run Docker container `docker compose up`
- Navigate to [localhost:9999](http://localhost:9999)

### Running Tests

- Run `docker compose run app vendor/bin/phpunit tests` from the project root
