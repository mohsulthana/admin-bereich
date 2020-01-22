# Gemueseeggli - Slim Framework based Solution

The best slim framework solution for small webapplications ever built by Vikings IT.

## Database

Create Database

* `vendor/bin/doctrine orm:schema-tool:create`

Update Database
* `vendor/bin/doctrine orm:schema-tool:update --force`

Delete Database
* `vendor/bin/doctrine orm:schema-tool:drop --force`

## Webserver & Webpack

Local Webserver
* `php -S 0.0.0.0:8080 -t public public/index.php`

Webpack Development Webserver (Sass/JS Compiler/Bundler) (FOR DEVELOPMENT)
* `./node_modules/.bin/webpack --config webpack/dev.config.js --watch`

Create Productive Minimized Bundles (Sass/JS Compiler/Bundler) (FOR PRODUCTION)
* `./node_modules/.bin/webpack --config webpack/prod.config.js`

## Requirements

* PHP 7.1.x
* A MySQL database
* Node.js / NPM
* [Composer](https://getcomposer.org/download/)

## Setup

* `npm install`
* `composer install`
* Change database connection credentials in `.env`
* Create database with `vendor/bin/doctrine orm:schema-tool:create`
* Create User in users table with isAdmin=true
* Launch local PHP Server from root folder `php -S 0.0.0.0:8080 -t public public/index.php`
* Launch local Webpack Development Server from root folder `./node_modules/.bin/webpack --watch`
* Enjoy