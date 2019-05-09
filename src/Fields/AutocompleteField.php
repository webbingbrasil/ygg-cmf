<?php

namespace Ygg\Fields;

use Illuminate\Support\Collection;
use Ygg\Exceptions\Form\FieldValidationException;
use Ygg\Fields\Formatters\AutocompleteFormatter;
use Ygg\Fields\Traits\FieldWithDataLocalization;
use Ygg\Fields\Traits\FieldWithOptions;
use Ygg\Fields\Traits\FieldWithPlaceholder;
use Ygg\Fields\Traits\FieldWithTemplates;

/**
 * Class AutocompleteField
 * @package Ygg\Fields
 */
class AutocompleteField extends AbstractField
{
    use FieldWithPlaceholder, FieldWithTemplates,
        FieldWithOptions, FieldWithDataLocalization;

    protected const FIELD_TYPE = 'autocomplete';

    /**
     * @var string
     */
    protected $mode = 'remote';

    /**
     * @var Collection|array
     */
    protected $localValues = [];

    /**
     * @var array
     */
    protected $localSearchKeys = ['value'];

    /**
     * @var string
     */
    protected $remoteMethod = 'GET';

    /**
     * @var string
     */
    protected $remoteEndpoint;

    /**
     * @var string
     */
    protected $remoteSearchAttribute = 'query';

    /**
     * @var string
     */
    protected $itemIdAttribute = 'id';

    /**
     * @var int
     */
    protected $searchMinChars = 3;

    /**
     * @param string $key
     * @return static
     */
    public static function make(string $key)
    {
        $instance = new static($key, static::FIELD_TYPE, new AutocompleteFormatter());

        return $instance;
    }

    /**
     * @param $localValues
     * @return $this
     */
    public function setLocalValues($localValues): self
    {
        $this->localValues = static::formatOptions($localValues);
        $this->mode = 'local';

        return $this;
    }

    /**
     * @param array $localSearchKeys
     * @return $this
     */
    public function setLocalSearchKeys(array $localSearchKeys): self
    {
        $this->localSearchKeys = $localSearchKeys;

        return $this;
    }

    /**
     * @param string $remoteEndpoint
     * @return $this
     */
    public function setRemoteEndpoint(string $remoteEndpoint): self
    {
        $this->remoteEndpoint = $remoteEndpoint;
        $this->mode = 'remote';

        return $this;
    }

    /**
     * @param string $remoteSearchAttribute
     * @return $this
     */
    public function setRemoteSearchAttribute(string $remoteSearchAttribute): self
    {
        $this->remoteSearchAttribute = $remoteSearchAttribute;

        return $this;
    }

    /**
     * @return $this
     */
    public function setRemoteMethodAsGet(): self
    {
        $this->remoteMethod = 'GET';

        return $this;
    }

    /**
     * @return $this
     */
    public function setRemoteMethodAsPost(): self
    {
        $this->remoteMethod = 'POST';

        return $this;
    }

    /**
     * @param string $itemIdAttribute
     * @return $this
     */
    public function setItemIdAttribute(string $itemIdAttribute): self
    {
        $this->itemIdAttribute = $itemIdAttribute;

        return $this;
    }

    /**
     * @param string $listItemTemplatePath
     * @return $this
     */
    public function setListItemTemplatePath(string $listItemTemplatePath): self
    {
        return $this->setTemplatePath($listItemTemplatePath, 'list');
    }

    /**
     * @param string $resultItemTemplate
     * @return $this
     */
    public function setResultItemTemplatePath(string $resultItemTemplate): self
    {
        return $this->setTemplatePath($resultItemTemplate, 'result');
    }

    /**
     * @param string $template
     * @return $this
     */
    public function setListItemInlineTemplate(string $template): self
    {
        return $this->setInlineTemplate($template, 'list');
    }

    /**
     * @param string $template
     * @return $this
     */
    public function setResultItemInlineTemplate(string $template): self
    {
        return $this->setInlineTemplate($template, 'result');
    }

    /**
     * @param int $searchMinChars
     * @return $this
     */
    public function setSearchMinChars(int $searchMinChars): self
    {
        $this->searchMinChars = $searchMinChars;

        return $this;
    }

    /**
     * @return bool
     */
    public function isRemote(): bool
    {
        return $this->mode === 'remote';
    }

    /**
     * @return bool
     */
    public function isLocal(): bool
    {
        return $this->mode === 'local';
    }

    /**
     * @return string
     */
    public function itemIdAttribute(): string
    {
        return $this->itemIdAttribute;
    }

    /**
     * @return array
     * @throws FieldValidationException
     */
    public function toArray(): array
    {
        return parent::buildArray([
            'mode' => $this->mode,
            'placeholder' => $this->placeholder,
            'localValues' => $this->localValues,
            'itemIdAttribute' => $this->itemIdAttribute,
            'searchKeys' => $this->localSearchKeys,
            'remoteEndpoint' => $this->remoteEndpoint,
            'remoteMethod' => $this->remoteMethod,
            'remoteSearchAttribute' => $this->remoteSearchAttribute,
            'listItemTemplate' => $this->template('list'),
            'resultItemTemplate' => $this->template('result'),
            'searchMinChars' => $this->searchMinChars,
            'localized' => $this->localized,
        ]);
    }

    /**
     * @return array
     */
    protected function validationRules(): array
    {
        return [
            'mode' => 'required|in:local,remote',
            'itemIdAttribute' => 'required',
            'listItemTemplate' => 'required',
            'resultItemTemplate' => 'required',
            'searchMinChars' => 'required|integer',
            'localValues' => 'array',
            'searchKeys' => 'required_if:mode,local|array',
            'remoteEndpoint' => 'required_if:mode,remote',
            'remoteMethod' => 'required_if:mode,remote|in:GET,POST',
            'remoteSearchAttribute' => 'required_if:mode,remote',
        ];
    }
}
