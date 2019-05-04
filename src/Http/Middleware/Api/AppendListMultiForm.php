<?php

namespace Ygg\Http\Middleware\Api;

use Closure;
use function count;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class AppendListMultiForm
 * @package Ygg\Http\Middleware\Api
 */
class AppendListMultiForm
{

    /**
     * @param Request $request
     * @param Closure $next
     * @return JsonResponse|mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Add Multiform data to the JSON returned
        return $response->status() === 200
            ? $this->addMultiformDataToJsonResponse($response)
            : $response;
    }

    /**
     * @param JsonResponse $jsonResponse
     * @return JsonResponse
     */
    protected function addMultiformDataToJsonResponse(JsonResponse $jsonResponse): JsonResponse
    {
        $multiformAttribute = $jsonResponse->getData()->config->multiformAttribute;
        $instanceIdAttribute = $jsonResponse->getData()->config->instanceIdAttribute;

        if (!$multiformAttribute) {
            return $jsonResponse;
        }

        $subFormKeys = collect($this->getMultiformKeys())
            ->map(function ($value) use ($instanceIdAttribute, $multiformAttribute, $jsonResponse) {
                $instanceIds = collect($jsonResponse->getData()->data->items)
                    ->where($multiformAttribute, $value)
                    ->pluck($instanceIdAttribute);

                return [
                        'key' => $value,
                        'label' => $this->getMultiformLabelFor($value),
                        'instances' => $instanceIds
                    ] + $this->getIconConfigFor($value);
            })
            ->keyBy('key');

        if (count($subFormKeys)) {
            $data = $jsonResponse->getData();
            $data->forms = $subFormKeys;
            $jsonResponse->setData($data);
        }

        return $jsonResponse;
    }

    /**
     * @return array
     */
    protected function getMultiformKeys(): array
    {
        $resourceKey = $this->determineResourceKey();

        $config = config('ygg.resources.'.$resourceKey.'.forms');

        return $config ? array_keys($config) : [];
    }

    /**
     * @return string|null
     */
    protected function determineResourceKey(): ?string
    {
        return request()->segment(4);
    }

    /**
     * @param string $formSubKey
     * @return mixed
     */
    protected function getMultiformLabelFor(string $formSubKey)
    {
        $resourceKey = $this->determineResourceKey();

        return config('ygg.resources.'.$resourceKey.'.forms.'.$formSubKey.'.label');
    }

    /**
     * @param string $formSubKey
     * @return array
     */
    protected function getIconConfigFor(string $formSubKey): array
    {
        $resourceKey = $this->determineResourceKey();

        $icon = config('ygg.resources.'.$resourceKey.'.forms.'.$formSubKey.'.icon');

        return $icon ? compact('icon') : [];
    }
}
