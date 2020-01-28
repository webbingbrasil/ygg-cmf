<?php

namespace Ygg\Old\Widgets;

use Illuminate\Support\Facades\Validator;
use Ygg\Old\Exceptions\Dashboard\WidgetValidationException;
use Ygg\Old\Utils\LinkToResource;

/**
 * Class Widget
 * @package Ygg\Old\Widgets
 */
abstract class Widget
{

    /**
     * @var string
     */
    protected $key;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $link;

    /**
     * Widget constructor.
     * @param string $key
     * @param string $type
     */
    protected function __construct(string $key, string $type)
    {
        $this->key = $key;
        $this->type = $type;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param string      $resourceKey
     * @param string|null $instanceId
     * @param array       $querystring
     * @return $this
     */
    public function setLink(string $resourceKey, string $instanceId = null, array $querystring = []): self
    {
        $this->link = (new LinkToResource('', $resourceKey))
            ->setInstanceId($instanceId)
            ->setFullQueryString($querystring)
            ->renderAsUrl();

        return $this;
    }

    public function unsetLink(): void
    {
        $this->link = null;
    }

    /**
     * @return array
     */
    abstract public function toArray(): array;

    /**
     * @param array $childArray
     * @return array
     * @throws WidgetValidationException
     */
    protected function buildArray(array $childArray): array
    {
        $array = collect([
                'key' => $this->key,
                'type' => $this->type,
                'title' => $this->title,
                'link' => $this->link
            ] + $childArray)
            ->filter(static function ($value) {
                return $value !== null;
            })->all();

        $this->validate($array);

        return $array;
    }

    /**
     * @param array $properties
     * @throws WidgetValidationException
     */
    protected function validate(array $properties): void
    {
        $validator = Validator::make($properties, [
                'key' => 'required',
                'type' => 'required',
            ] + $this->validationRules());

        if ($validator->fails()) {
            throw new WidgetValidationException($validator->errors());
        }
    }

    /**
     * Return specific validation rules.
     *
     * @return array
     */
    protected function validationRules(): array
    {
        return [];
    }
}
