# Laravel CRUDIFY

Easy things to generate CRUD with laravel.

## Installation

```sh
composer require defrindr/crudify
```

## Usage

1. Running generator
  ```sh
  php artisan crudify:gen --module={module name} {model name}
  ```
  It will generate Model, Controller, Service, Request, and Resource in same time
2. Register to route
  ```php
  Route::resource(ExampleController::class);
  ```


## Publish Package

```sh
php artisan vendor:publish --provider="Defrindr\Crudify\CrudifyServiceProvider"
```
