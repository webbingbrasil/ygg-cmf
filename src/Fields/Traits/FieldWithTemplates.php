<?php

namespace Ygg\Fields\Traits;

/**
 * Trait FieldWithTemplates
 * @package Ygg\Fields\Traits
 */
trait FieldWithTemplates
{
    /**
     * @var array
     */
    protected $templates = [];

    /**
     * @param string $templatePath
     * @param string $key
     * @return $this
     */
    protected function setTemplatePath(string $templatePath, string $key): self
    {
        return $this->setInlineTemplate(
            file_get_contents(resource_path('views/'.$templatePath)),
            $key
        );
    }

    /**
     * @param string $template
     * @param string $key
     * @return $this
     */
    protected function setInlineTemplate(string $template, string $key): self
    {
        $this->templates[$key] = $template;

        return $this;
    }

    /**
     * @param string $key
     * @return string|null
     */
    protected function template(string $key): ?string
    {
        return $this->templates[$key] ?? null;
    }
}
