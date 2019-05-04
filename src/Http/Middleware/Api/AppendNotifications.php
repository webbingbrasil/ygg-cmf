<?php

namespace Ygg\Http\Middleware\Api;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class AppendNotifications
 * @package Ygg\Http\Middleware\Api
 */
class AppendNotifications
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return JsonResponse|mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        return $response->status() === 200
            ? $this->addNotificationsToResponse($response)
            : $response;
    }

    /**
     * @param JsonResponse $jsonResponse
     * @return JsonResponse|mixed
     */
    protected function addNotificationsToResponse(JsonResponse $jsonResponse)
    {
        if (!$notifications = session('ygg_notifications')) {
            return $jsonResponse;
        }

        session()->forget('ygg_notifications');

        return tap($jsonResponse, function (JsonResponse $jsonResponse) use ($notifications) {
            $data = $jsonResponse->getData();
            $data->notifications = array_values($notifications);
            $jsonResponse->setData($data);
        });
    }
}
