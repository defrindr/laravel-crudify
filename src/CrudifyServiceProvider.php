<?php

namespace Defrindr\Crudify;

use Defrindr\Crudify\Exceptions\BadRequestHttpException;
use Defrindr\Crudify\Exceptions\ForbiddenHttpException;
use Defrindr\Crudify\Exceptions\UnauthenticatedHttpException;
use Defrindr\Crudify\Exceptions\UnprocessableEntityHttpException;
use Defrindr\Crudify\Helpers\ResponseHelper;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class CrudifyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap services.
     */
    public function boot(Router $router): void
    {
        $this->app->afterResolving(
            \Illuminate\Foundation\Exceptions\Handler::class,
            function ($handler) {
                $exceptions = new Exceptions($handler);
                $exceptions->render(function (Exception $e, Request $request) {
                    if ($request->wantsJson()) {
                        if ($e instanceof AuthenticationException) {
                            return ResponseHelper::unAuthencation();
                        } else if ($e instanceof BadRequestHttpException) {
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
                        } else {
                            return ResponseHelper::error($e);
                        }
                    }
                });
            },
        );

        $this->loadRoutesFrom(__DIR__ . '/web.php');
    }
}
