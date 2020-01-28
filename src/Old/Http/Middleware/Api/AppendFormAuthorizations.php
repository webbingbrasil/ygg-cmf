<?php

namespace Ygg\Old\Http\Middleware\Api;

use Closure;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class AppendFormAuthorizations
 * @package Ygg\Old\Http\Middleware\Api
 */
class AppendFormAuthorizations
{

    /**
     * @var Gate
     */
    protected $gate;

    /**
     * @param Gate $gate
     */
    public function __construct(Gate $gate)
    {
        $this->gate = $gate;
    }

    /**
     * @param Request $request
     * @param Closure $next
     * @return JsonResponse|mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Add authorization to the JSON returned
        return $response->status() === 200
            ? $this->addAuthorizationsToJsonResponse($response)
            : $response;
    }

    /**
     * @param JsonResponse $jsonResponse
     * @return JsonResponse
     */
    protected function addAuthorizationsToJsonResponse(JsonResponse $jsonResponse): JsonResponse
    {
        [$resourceKey, $instanceId] = $this->determineResourceKeys();

        $policies = [];

        if ($this->hasPolicyFor($resourceKey)) {
            $policies = [
                'create' => $this->gate->check('ygg.'.$resourceKey.'.create'),
            ];

            if ($instanceId) {
                $policies += [
                    'view' => $this->gate->check('ygg.'.$resourceKey.'.view', $instanceId),
                    'update' => $this->gate->check('ygg.'.$resourceKey.'.update', $instanceId),
                    'delete' => $this->gate->check('ygg.'.$resourceKey.'.delete', $instanceId)
                ];
            }
        }

        $globalAuthorizations = $this->getGlobalAuthorizations($resourceKey);
        $data = $jsonResponse->getData();

        $data->authorizations = array_merge(
            ['view' => true, 'create' => true, 'update' => true, 'delete' => true],
            $policies,
            (array)$globalAuthorizations
        );

        $jsonResponse->setData($data);

        return $jsonResponse;
    }

    /**
     * @return array
     */
    protected function determineResourceKeys(): array
    {
        [$resourceKey, $instanceId] = [
            request()->segment(4),
            request()->segment(5)
        ];

        if (($pos = strpos($resourceKey, ':')) !== false) {
            $resourceKey = substr($resourceKey, 0, $pos);
        }

        return [$resourceKey, $instanceId];
    }

    /**
     * @param $resourceKey
     * @return bool
     */
    protected function hasPolicyFor($resourceKey): bool
    {
        return config('ygg.resources.'.$resourceKey.'.policy') !== null;
    }

    /**
     * @param string $resourceKey
     * @return mixed
     */
    protected function getGlobalAuthorizations(string $resourceKey)
    {
        return config('ygg.resources.'.$resourceKey.'.authorizations', []);
    }
}
