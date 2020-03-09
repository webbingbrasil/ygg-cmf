<?php

namespace Ygg\Screen\Contracts;

interface Searchable
{
    /**
     * @return string
     */
    public function label(): string;

    /**
     * @return string
     */
    public function title(): string;

    /**
     * @return string
     */
    public function subTitle(): string;

    /**
     * @return string
     */
    public function url(): string;

    /**
     * @return string
     */
    public function image(): ?string;
}
