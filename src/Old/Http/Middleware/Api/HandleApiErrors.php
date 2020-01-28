<?php

namespace Ygg\Old\Http\Middleware\Api;

use Closure;
use Illuminate\Http\Request;
use Ygg\Old\Exceptions\Auth\AuthorizationException;
use Ygg\Old\Exceptions\Form\ApplicativeException;
use Ygg\Old\Exceptions\Form\FieldValidationException;
use Ygg\Old\Exceptions\Resource\InvalidResourceStateException;
use Ygg\Old\Exceptions\InvalidResourceKeyException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class HandleApiErrors
{

    /**
     * @param Request $request
     * @param Closure $next
     * @param null    $guard
     * @return JsonResponse|mixed
     */
    public function handle(Request $request, Closure $next, $guard = null)
    {
        $response = $next($request);

        if (isset($response->exception) && $response->exception) {
            if ($response->exception instanceof ValidationException) {
                return $this->handleValidationException($response);
            }

            return response()->json([
                'message' => $response->exception->getMessage()
            ], $this->getHttpCodeFor($response->exception));
        }

        return $response;
    }

    /**
     * @param $response
     * @return JsonResponse
     */
    protected function handleValidationException($response): JsonResponse
    {
        return response()->json([
            'message' => $response->exception->getMessage(),
            'errors' => $response->exception->errors()
        ], 422);
    }

    /**
     * @param $exception
     * @return int
     */
    private function getHttpCodeFor($exception): int
    {
        if ($exception instanceof ApplicativeException) {
            return 417;
        }

        if ($exception instanceof AuthorizationException) {
            return 403;
        }

        if ($exception instanceof InvalidResourceKeyException
            || $exception instanceof ModelNotFoundException) {
            return 404;
        }

        if ($exception instanceof InvalidResourceStateException) {
            return 422;
        }

        if ($exception instanceof FieldValidationException) {
            return 500;
        }

        if (method_exists($exception, 'getStatusCode')) {
            return $exception->getStatusCode();
        }

        return 500;
    }
}
