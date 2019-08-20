<?php

namespace Ygg\Widgets;

use Ygg\Exceptions\Dashboard\WidgetValidationException;
use Ygg\Fields\HtmlField;

/**
 * Class PanelWidget
 * @package Ygg\Widgets
 */
class PanelWidget extends Widget
{

    /**
     * @var HtmlField
     */
    protected $htmlFormField;

    /**
     * @param string $key
     * @return $this
     */
    public static function make(string $key): self
    {
        $widget = new static($key, 'panel');
        $widget->htmlFormField = HtmlField::make('panel');

        return $widget;
    }

    /**
     * @return array
     * @throws WidgetValidationException
     */
    public function toArray(): array
    {
        return parent::buildArray([
            'template' => $this->htmlFormField->template()
        ]);
    }

    /**
     * @param string $templatePath
     * @return $this
     */
    public function setTemplatePath(string $templatePath): self
    {
        $this->htmlFormField->setTemplatePath($templatePath);

        return $this;
    }

    /**
     * @param string $template
     * @return $this
     */
    public function setInlineTemplate(string $template): self
    {
        $this->htmlFormField->setInlineTemplate($template);

        return $this;
    }

    /**
     * @return array
     */
    protected function validationRules(): array
    {
        return [
            'template' => 'required'
        ];
    }
}
