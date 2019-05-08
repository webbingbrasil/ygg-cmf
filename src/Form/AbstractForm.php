<?php

namespace Ygg\Form;

use Closure;
use function count;
use function get_class;
use stdClass;
use Ygg\Exceptions\Form\FormUpdateException;
use Ygg\Layout\Element;
use Ygg\Layout\Form\FieldRow;
use Ygg\Layout\Form\FormRow;
use Ygg\Layout\Layout;
use Ygg\Layout\Form\Tab;
use Ygg\Layout\WithElements;
use Ygg\Resource\HandleFields;
use Ygg\Traits\Transformers\WithTransformers;
use Ygg\Utils\Notification;

/**
 * Class Form
 * @package Ygg\Form
 */
abstract class AbstractForm implements Form
{
    use WithTransformers, HandleFields, WithElements;

    /**
     * @var bool
     */
    private $layoutBuilt = false;

    /**
     * @return array
     */
    public function getLayout(): array
    {
        if (!$this->layoutBuilt) {
            $this->layout();
            $this->layoutBuilt = true;
        }

        return [
            'tabbed' => count($this->elements) > 1,
            'tabs' => collect($this->elements)->map(function (Element $element) {
                return $element->toArray();
            })->all()
        ];
    }

    /**
     * Build form layout using TabbedLayout or ContainerLayout
     *
     * @return void
     */
    abstract protected function layout(): void;

    /**
     * @param string        $label
     * @param Closure|null $callback
     * @return $this
     */
    protected function addTab(string $label, Closure $callback = null): self
    {
        $this->layoutBuilt = false;
        $this->addElement(new Tab($label), $callback);

        return $this;
    }

    /**
     * @return Tab
     */
    private function getDefaultTab(): Tab
    {
        if(count($this->elements) === 0) {
            $this->addTab('');
        }

        return $this->elements[0];
    }

    /**
     * @param int          $size
     * @param Closure|null $callback
     * @return $this
     */
    protected function addColumn(int $size = 12, Closure $callback = null): self
    {
        $this->layoutBuilt = false;
        $this->getDefaultTab()->addColumn($size, $callback);
        return $this;
    }

    /**
     * @param $id
     * @return array
     */
    public function getResourceById($id): array
    {
        return collect($this->find($id))
            // Filter model attributes on actual form fields
            ->only($this->getFieldKeys())
            ->all();
    }

    /**
     * Retrieve a Model for the form and pack all its data as JSON.
     *
     * @param $id
     * @return array
     */
    abstract protected function find($id): array;

    /**
     * @return array
     */
    public function newResource(): ?array
    {
        $data = collect($this->create())
            // Filter model attributes on actual form fields
            ->only($this->getFieldKeys())
            ->all();

        return count($data) ? $data : null;
    }

    /**
     * Pack new Model data as JSON.
     *
     * @return array
     */
    private function create(): array
    {
        $attributes = collect($this->getFieldKeys())
            ->flip()
            ->map(function () {
                return null;
            })->all();

        // Build a fake Model class based on attributes
        return $this->transform(new class($attributes) extends stdClass
        {
            public function __construct($attributes)
            {
                $this->attributes = $attributes;

                foreach ($attributes as $name => $value) {
                    $this->$name = $value;
                }
            }

            public function toArray()
            {
                return $this->attributes;
            }
        });
    }

    /**
     * @return bool
     */
    public function hasDataLocalizations(): bool
    {
        return collect($this->getFields())
                ->filter(function ($field) {
                    return $field['localized'] ?? false;
                })
                ->count() > 0;
    }

    /**
     * @return array
     */
    public function getDataLocalizations(): array
    {
        return [];
    }

    /**
     * @param $data
     * @throws FormUpdateException
     */
    public function storeResource($data): void
    {
        $this->updateResource(null, $data);
    }

    /**
     * @param $id
     * @param $data
     * @throws FormUpdateException
     */
    public function updateResource($id, $data): void
    {
        [$formattedData, $delayedData] = $this->formatRequestData($data, $id, true);

        $id = $this->update($id, $formattedData);

        if ($delayedData) {
            // Some formatters asked to delay their handling after a first pass.
            // Typically, this is used if the formatter needs the id of the
            // instance: in a creation case, we must store it first.
            if (!$id) {
                throw new FormUpdateException(
                    sprintf('The update method of [%s] must return the instance id', basename(get_class($this)))
                );
            }

            $this->update($id, $this->formatRequestData($delayedData, $id, false));
        }
    }

    /**
     * @param mixed $id
     * @param array $data
     * @return mixed
     */
    abstract protected function update($id, array $data);

    /**
     * Display a notification next time resource list is shown.
     *
     * @param string $title
     * @return Notification
     */
    protected function notify(string $title): Notification
    {
        return new Notification($title);
    }

    /**
     * @param $id
     */
    abstract public function deleteResourceById($id): void;
}
