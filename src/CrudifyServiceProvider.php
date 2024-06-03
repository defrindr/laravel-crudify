<?php

namespace Defrindr\Crudify;

use Defrindr\Crudify\Exceptions\BadRequestHttpException;
use Defrindr\Crudify\Exceptions\ForbiddenHttpException;
use Defrindr\Crudify\Exceptions\UnauthenticatedHttpException;
use Defrindr\Crudify\Exceptions\UnprocessableEntityHttpException;
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

                    if ($request->wantsJson()) {
                        if ($e instanceof BadRequestHttpException) {
                            return ResponseHelper::badRequest($e->getMessage());
                        } else if ($e instanceof ForbiddenHttpException) {
                            return ResponseHelper::unAuthorization($e->getMessage());
                        } else if ($e instanceof MethodNotAllowedHttpException) {
                            return ResponseHelper::methodNotAllowed($e->getMessage());
                        } else if ($e instanceof ModelNotFoundException) {
                            return ResponseHelper::modelNotFound($e->getMessage());
                        } else if ($e instanceof UnauthenticatedHttpException) {
                            return ResponseHelper::error($e);
                        } else if ($e instanceof UnprocessableEntityHttpException) {
                            return ResponseHelper::conflict($e->getMessage());
                        }
                    }
                });
            },
        );

        $this->loadRoutesFrom(__DIR__ . '/web.php');
    }
}
