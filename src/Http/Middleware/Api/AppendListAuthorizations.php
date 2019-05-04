<?php

namespace Ygg\Http\Middleware\Api;

use function count;
use Closure;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class AppendListAuthorizations
 * @package Ygg\Http\Middleware\Api
 */
class AppendListAuthorizations
{
    /**
     * The gate instance.
     *
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
        $resourceKey = $this->determineResourceKey();

        $authorizations = $this->getGlobalAuthorizations($resourceKey);

        if (count($authorizations) !== 3 && $this->hasPolicyFor($resourceKey)) {
            // Have to dig into policies

            if (!isset($authorizations['create'])) {
                // Create doesn't need instanceId
                $authorizations['create'] = $this->gate->check('ygg.'.$resourceKey.'.create');
            }

            // Collect instanceIds from response
            $instanceIdAttribute = $jsonResponse->getData()->config->instanceIdAttribute;
            $instanceIds = collect($jsonResponse->getData()->data->items)->pluck($instanceIdAttribute);

            $missingAbilities = array_diff(['view', 'update'], array_keys($authorizations));

            foreach ($instanceIds as $instanceId) {
                foreach ($missingAbilities as $missingAbility) {
                    if ($this->gate->check('ygg.'.$resourceKey.'.'.$missingAbility, $instanceId)) {
                        $authorizations[$missingAbility][] = $instanceId;

                    } elseif (!isset($authorizations[$missingAbility])) {
                        $authorizations[$missingAbility] = [];
                    }
                }
            }

            foreach ($missingAbilities as $missingAbility) {
                if (isset($authorizations[$missingAbility]) && count($authorizations[$missingAbility]) === 0) {
                    $authorizations[$missingAbility] = false;
                }
            }
        }

        // All missing abilities are true by default
        $authorizations = array_merge(
            ['view' => true, 'create' => true, 'update' => true],
            $authorizations
        );

        $data = $jsonResponse->getData();

        $data->authorizations = $authorizations;

        $jsonResponse->setData($data);

        return $jsonResponse;
    }

    /**
     * @return string|null
     */
    protected function determineResourceKey(): ?string
    {
        return request()->segment(4);
    }

    /**
     * @param string $resourceKey
     * @return mixed
     */
    protected function getGlobalAuthorizations(string $resourceKey)
    {
        return config('ygg.resources.'.$resourceKey.'.authorizations', []);
    }

    /**
     * @param $resourceKey
     * @return bool
     */
    protected function hasPolicyFor($resourceKey): bool
    {
        return config('ygg.resources.{$resourceKey}.policy') !== null;
    }
}
