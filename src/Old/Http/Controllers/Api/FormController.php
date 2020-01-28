<?php

namespace Ygg\Old\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Ygg\Old\Exceptions\Form\FormUpdateException;
use Ygg\Old\Exceptions\InvalidResourceKeyException;
use Ygg\Old\Form\Form;

/**
 * Class FormController
 * @package Ygg\Old\Http\Controllers\Api
 */
class FormController extends ApiController
{

    /**
     * @param string $resourceKey
     * @param string $instanceId
     * @return JsonResponse
     * @throws InvalidResourceKeyException
     */
    public function edit(string $resourceKey, string $instanceId): JsonResponse
    {
        ygg_check_ability('view', $resourceKey, $instanceId);

        $form = $this->getFormInstance($resourceKey);

        return response()->json([
                'fields' => $form->getFields(),
                'layout' => $form->getLayout(),
                'data' => $form->getResourceById($instanceId)
            ] + $this->dataLocalizations($form));
    }

    /**
     * @param Form $form
     * @return array
     */
    protected function dataLocalizations(Form $form): array
    {
        return $form->hasDataLocalizations()
            ? ['locales' => $form->getDataLocalizations()]
            : [];
    }

    /**
     * @param string $resourceKey
     * @return JsonResponse
     * @throws InvalidResourceKeyException
     */
    public function create(string $resourceKey): JsonResponse
    {
        ygg_check_ability('create', $resourceKey);

        $form = $this->getFormInstance($resourceKey);

        return response()->json([
                'fields' => $form->getFields(),
                'layout' => $form->getLayout(),
                'data' => $form->newResource()
            ] + $this->dataLocalizations($form));
    }

    /**
     * @param string $resourceKey
     * @param string $instanceId
     * @return JsonResponse
     * @throws InvalidResourceKeyException
     * @throws FormUpdateException
     */
    public function update(string $resourceKey, string $instanceId): JsonResponse
    {
        ygg_check_ability('update', $resourceKey, $instanceId);

        $this->validateRequest($resourceKey);

        $form = $this->getFormInstance($resourceKey);

        $form->updateResource($instanceId, request()->all());

        return response()->json(['ok' => true]);
    }

    /**
     * @param string $resourceKey
     */
    protected function validateRequest(string $resourceKey): void
    {
        if ($this->isSubResource($resourceKey)) {
            [$resourceKey, $subResourceKey] = explode(':', $resourceKey);
            $validatorClass = config('ygg.resources.'.$resourceKey.'.forms.'.$subResourceKey.'.validator');

        } else {
            $validatorClass = config('ygg.resources.'.$resourceKey.'.validator');
        }

        if (class_exists($validatorClass)) {
            app($validatorClass);
        }
    }

    /**
     * @param string $resourceKey
     * @return JsonResponse
     * @throws FormUpdateException
     * @throws InvalidResourceKeyException
     */
    public function store(string $resourceKey): JsonResponse
    {
        ygg_check_ability('create', $resourceKey);

        $this->validateRequest($resourceKey);

        $form = $this->getFormInstance($resourceKey);

        $form->storeResource(request()->all());

        return response()->json(['ok' => true]);
    }

    /**
     * @param string $resourceKey
     * @param string $instanceId
     * @return JsonResponse
     * @throws InvalidResourceKeyException
     */
    public function delete(string $resourceKey, string $instanceId): JsonResponse
    {
        ygg_check_ability('delete', $resourceKey, $instanceId);

        $form = $this->getFormInstance($resourceKey);

        $form->delete($instanceId);

        return response()->json(['ok' => true]);
    }
}
