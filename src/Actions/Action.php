<?php

namespace Ygg\Actions;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Throwable;
use Ygg\Form\HandleFormFields;
use Ygg\Form\Layout\FormLayoutColumn;
use Ygg\Traits\Transformers\WithCustomTransformers;

/**
 * Class Action
 * @package Ygg\Actions
 */
abstract class Action
{
    use HandleFormFields, WithCustomTransformers;

    protected $groupIndex = 0;

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

    public function buildFormFields(): void
    {
    }

    /**
     * @return array
     */
    public function form(): array
    {
        return $this->fields();
    }

    /**
     * @return array|null
     */
    public function formLayout(): ?array
    {
        if (!$this->fields) {
            return null;
        }

        $column = new FormLayoutColumn(12);
        $this->buildFormLayout($column);

        if (empty($column->fieldsToArray()['fields'])) {
            foreach ($this->fields as $field) {
                $column->withSingleField($field->key());
            }
        }

        return $column->fieldsToArray()['fields'];
    }

    /**
     * @param FormLayoutColumn $column
     */
    public function buildFormLayout(FormLayoutColumn $column): void
    {
    }

    /**
     * @param $index
     */
    public function setGroupIndex($index): void
    {
        $this->groupIndex = $index;
    }

    /**
     * @return int
     */
    public function groupIndex(): int
    {
        return $this->groupIndex;
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

    /**
     * @return string
     */
    public function description(): string
    {
        return '';
    }

    /**
     * @return string
     */
    abstract public function label(): string;

    /**
     * @param string $message
     * @return array|void
     */
    protected function info(string $message): ?array
    {
        return [
            'action' => 'info',
            'message' => $message
        ];
    }

    /**
     * @param string $link
     * @return array
     */
    protected function link(string $link): array
    {
        return [
            'action' => 'link',
            'link' => $link
        ];
    }

    /**
     * @return array
     */
    protected function reload(): array
    {
        return [
            'action' => 'reload'
        ];
    }

    /**
     * @param $ids
     * @return array
     */
    protected function refresh($ids): array
    {
        return [
            'action' => 'refresh',
            'items' => (array)$ids
        ];
    }

    /**
     * @param string $bladeView
     * @param array  $params
     * @return array|void
     * @throws Throwable
     */
    protected function view(string $bladeView, array $params = []): ?array
    {
        return [
            'action' => 'view',
            'html' => view($bladeView, $params)->render()
        ];
    }

    /**
     * @param string $filePath
     * @param null   $fileName
     * @param null   $diskName
     * @return array
     */
    protected function download(string $filePath, $fileName = null, $diskName = null): array
    {
        return [
            'action' => 'download',
            'file' => $filePath,
            'disk' => $diskName,
            'name' => $fileName
        ];
    }
}
