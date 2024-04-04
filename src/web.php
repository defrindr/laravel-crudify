<?php

use Defrindr\Crudify\CrudGenerator;
use Illuminate\Support\Facades\Route;

if ($this->app->runningInConsole()) {
  $this->commands([
    CrudGenerator::class
  ]);
  $this->publishes([
    __DIR__ . '/../config/crudify.php' => config_path('crudify.php'),
  ], 'config');

  $this->publishes([
    __DIR__ . '/../stubs' => base_path('stubs'),
  ]);
}

Route::get('/joss', function () {
  return "JOSSS";
});
