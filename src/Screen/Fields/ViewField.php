<?php

namespace Ygg\Screen\Fields;

use Ygg\Screen\Field;

/**
 * Class ViewField.
 *
 * @method ViewField name(string $value = null)
 * @method ViewField help(string $value = null)
 */
class ViewField extends Field
{
    /**
     * @param string $view
     *
     * @return ViewField
     */
    public function view(string $view): self
    {
        $this->view = $view;

        return $this;
    }

    /**
     * @param string|null $name
     *
     * @return ViewField
     */
    public static function make(string $name = null): self
    {
        return (new static())->name($name);
    }
}
