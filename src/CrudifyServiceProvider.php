<?php

namespace Defrindr\Crudify;

use Illuminate\Support\ServiceProvider;

class CrudifyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . "/web.php");
    }
}
