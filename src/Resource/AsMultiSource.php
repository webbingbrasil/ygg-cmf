<?php

namespace Ygg\Resource;

use Illuminate\Support\Arr;

trait AsMultiSource
{
    use AsSource {
        getContent as getBaseContent;
    }
    /**
     * Name row localization.
     *
     * @var string
     */
    public $jsonColumnName = 'content';

    /**
     * @param string $field
     * @param null   $locale
     *
     * @return mixed|null
     */
    public function getContent(string $field, $locale = null)
    {
        return $this->getBaseContent($field) ?? $this->getContentMultiLang($field, $locale);
    }

    /**
     * @param string $field
     * @param null   $locale
     *
     * @return mixed
     */
    private function getContentMultiLang(string $field, $locale = null)
    {
        $jsonContent = (array) $this->getAttribute($this->jsonColumnName);
        $fullName = ($locale ?? app()->getLocale()).'.'.$field;

        if (!Arr::has($jsonContent, $fullName)) {
            $fullName = config('app.fallback_locale').'.'.$field;
        }

        if (!Arr::has($jsonContent, $fullName)) {
            $fullName = $field;
        }

        return Arr::get($jsonContent, $fullName);
    }
}
