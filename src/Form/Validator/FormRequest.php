<?php

namespace Ygg\Form\Validator;

use function is_array;
use function in_array;
use function array_key_exists;
use Illuminate\Foundation\Http\FormRequest as BaseFormRequest;
use Ygg\Traits\WithContext;

/**
 * Class FormRequest
 * @package Ygg\Form\Validator
 */
abstract class FormRequest extends BaseFormRequest
{
    use WithContext;

    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    abstract public function rules(): array;

    /**
     * Handle RTF (markdown and wysiwyg) fields
     *
     * @param Validator $validator
     * @return void
     */
    public function withValidator(Validator $validator): void
    {
        // Find RTF (markdown, wysiwyg) based on their posted structure ($field["text"])
        $richTextFields = collect($this->all())->filter(function ($value, $key) {
            return is_array($value) && array_key_exists('text', $value);
        })->keys()->all();

        // Initialize rules by getting all those which DO NOT refer to a RTF
        $newRules = collect($validator->getRules())
            ->filter(function ($messages, $key) use ($richTextFields) {
                return !in_array($key, $richTextFields, false);
            })
            ->all();

        // And then replace RTF rules with .text suffix
        collect($validator->getRules())->filter(function ($messages, $key) use ($richTextFields) {
            return in_array($key, $richTextFields, false);
        })->each(function ($messages, $key) use (&$newRules) {
            $newRules[$key.'.text'] = $messages;
        });

        $validator->setRules($newRules);
    }
}
