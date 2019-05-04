<?php

namespace Ygg\Form;

use function count;
use function get_class;
use function in_array;
use stdClass;
use Ygg\Exceptions\Form\FieldFormattingMustBeDelayedException;
use Ygg\Exceptions\Form\FormUpdateException;
use Ygg\Layout\ContainerLayout;
use Ygg\Layout\Form\FormColumn;
use Ygg\Layout\Layout;
use Ygg\Layout\TabbedLayout;
use Ygg\Traits\Transformers\WithTransformers;
use Ygg\Utils\Notification;

/**
 * Class Form
 * @package Ygg\Form
 */
abstract class Form
{
    use WithTransformers, HandleFields;

    /**
     * @var array
     */
    protected $tabs = [];

    /**
     * @var Layout
     */
    protected $formLayout;

    /**
     * @var bool
     */
    protected $layoutBuilt = false;

    /**
     * Applies Field Formatters on $data.
     *
     * @param array       $data
     * @param string|null $instanceId
     * @param bool        $handleDelayedData
     * @return array
     */
    public function formatRequestData(array $data, $instanceId = null, bool $handleDelayedData = false): array
    {
        $delayedData = collect([]);

        $formattedData = collect($data)->filter(function ($value, $key) {
            // Filter only configured fields
            return in_array($key, $this->getFieldKeys(), false);

        })->map(function ($value, $key) use ($handleDelayedData, $delayedData, $instanceId) {
            if (!$field = $this->findFieldByKey($key)) {
                return $value;
            }

            try {
                // Apply formatter based on field configuration
                return $field->formatter()
                    ->setInstanceId($instanceId)
                    ->fromFront($field, $key, $value);

            } catch (FieldFormattingMustBeDelayedException $exception) {
                // The formatter needs to be executed in a second pass. We delay it.
                if ($handleDelayedData) {
                    $delayedData[$key] = $value;
                    return null;
                }

                throw $exception;
            }

        });

        if ($handleDelayedData) {
            return [
                $formattedData->filter(function ($value, $key) use ($delayedData) {
                    return !$delayedData->has($key);
                })->all(),
                $delayedData->all()
            ];
        }

        return $formattedData->all();
    }

    /**
     * @return array
     */
    public function formLayout(): array
    {
        if (!$this->layoutBuilt) {
            $this->buildFormLayout();
            $this->layoutBuilt = true;
        }

        if($this->formLayout) {
            return $this->formLayout->toArray();
        }

        return [];
    }

    /**
     * Build form layout using TabbedLayout or ContainerLayout
     *
     * @return void
     */
    abstract public function buildFormLayout(): void;

    /**
     * @return Layout|TabbedLayout
     */
    public function withTabbedLayout(): TabbedLayout
    {
        $this->formLayout = new TabbedLayout();
        $this->formLayout->setRowColumnClass(FormColumn::class);
        return $this->formLayout;
    }

    /**
     * @return Layout|ContainerLayout
     */
    public function withContainerLayout(): ContainerLayout
    {
        $this->formLayout = new ContainerLayout();
        $this->formLayout->setRowColumnClass(FormColumn::class);
        return $this->formLayout;
    }

    /**
     * Return the resource instance, as an array.
     *
     * @param $id
     * @return array
     */
    public function instance($id): array
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
    abstract public function find($id): array;

    /**
     * Return a new resource instance, as an array.
     *
     * @return array
     */
    public function newInstance(): ?array
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
    public function create(): array
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
    public function storeInstance($data): void
    {
        $this->updateInstance(null, $data);
    }

    /**
     * @param $id
     * @param $data
     * @throws FormUpdateException
     */
    public function updateInstance($id, $data): void
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
    abstract public function update($id, array $data);

    /**
     * Display a notification next time resource list is shown.
     *
     * @param string $title
     * @return Notification
     */
    public function notify(string $title): Notification
    {
        return new Notification($title);
    }

    /**
     * @param $id
     */
    abstract public function delete($id): void;

    /**
     * Build form fields using ->addField()
     *
     * @return void
     */
    abstract public function buildFormFields(): void;
}
