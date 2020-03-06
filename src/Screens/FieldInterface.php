<?php

namespace Ygg\Screens;

interface FieldInterface
{
    /**
     * The process of creating.
     *
     * @return mixed
     */
    public function render();

    /**
     * @param string $key
     * @param mixed  $value
     *
     * @return $this
     */
    public function get(string $key, $value = null);

    /**
     * @param string $key
     * @param mixed  $value
     *
     * @return $this
     */
    public function set(string $key, $value);

    /**
     * @return array
     */
    public function getAttributes(): array;
}
