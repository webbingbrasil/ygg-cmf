<?php

namespace Ygg\Fields;

use Ygg\Exceptions\Form\FieldValidationException;
use Ygg\Fields\Formatters\HtmlFormatter;
use Ygg\Fields\Formatters\TextareaFormatter;
use Ygg\Fields\Traits\FieldWithTemplates;

/**
 * Class HtmlField
 * @package Ygg\Fields
 */
class HtmlField extends AbstractField
{
    use FieldWithTemplates {
        setTemplatePath as protected parentSetTemplatePath;
        setInlineTemplate as protected parentSetInlineTemplate;
        template as protected parentTemplate;
    }

    protected const FIELD_TYPE = 'html';

    /**
     * @param string $key
     * @return static
     */
    public static function make(string $key): HtmlField
    {
        return new static($key, static::FIELD_TYPE, new HtmlFormatter());
    }

    /**
     * @param string $templatePath
     * @return static
     */
    public function setTemplatePath(string $templatePath): HtmlField
    {
        return $this->parentSetTemplatePath($templatePath, 'html');
    }

    /**
     * @param string $template
     * @return static
     */
    public function setInlineTemplate(string $template): HtmlField
    {
        return $this->parentSetInlineTemplate($template, 'html');
    }

    /**
     * @return string|null
     */
    public function template():? string
    {
        return $this->parentTemplate('html');
    }

    /**
     * @return array
     * @throws FieldValidationException
     */
    public function toArray(): array
    {
        return $this->buildArray([
            'template' => $this->template(),
        ]);
    }

    /**
     * @return array
     */
    protected function validationRules(): array
    {
        return [
            'template' => 'required1',
        ];
    }
}
