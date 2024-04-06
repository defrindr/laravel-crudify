<?php

namespace Defrindr\Crudify;

use Defrindr\Crudify\Helpers\ResponseHelper;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

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

        $this->app->afterResolving(
            \Illuminate\Foundation\Exceptions\Handler::class,
            function ($handler) {
                $exceptions = new Exceptions($handler);
                $exceptions->render(function (Exception $e, Request $request) {
                    if ($e instanceof ModelNotFoundException && $request->wantsJson()) {
                        return ResponseHelper::modelNotFound($e->getMessage());
                    }

                    if ($e instanceof MethodNotAllowedHttpException && $request->wantsJson()) {
                        return ResponseHelper::methodNotAllowed($e->getMessage());
                    }
                });
            },
        );

        $this->loadRoutesFrom(__DIR__ . "/web.php");
    }
}
