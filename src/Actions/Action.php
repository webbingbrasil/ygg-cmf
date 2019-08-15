<?php

namespace Ygg\Actions;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Throwable;
use Ygg\Traits\Transformers\WithTransformers;

/**
 * Class Action
 * @package Ygg\Actions
 */
abstract class Action
{
    use WithTransformers, WithBasicResponseActions;

    abstract public function type(): string;

    /**
     * @return array|bool
     */
    public function getGlobalAuthorization()
    {
        return $this->authorize();
    }

    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return string|null
     */
    public function confirmationText(): ?string
    {
        return null;
    }

    /**
     * @param array $params
     * @param array $rules
     * @param array $messages
     * @throws ValidationException
     */
    public function validate(array $params, array $rules, array $messages = []): void
    {
        $validator = Validator::make($params, $rules, $messages);

        if ($validator->fails()) {
            throw new ValidationException(
                $validator, new JsonResponse($validator->errors()->getMessages(), 422)
            );
        }
    }
}
