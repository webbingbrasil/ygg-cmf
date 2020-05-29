<?php

namespace Ygg\Actions;

use Ygg\Screen\Fields\RadioButtons;
use Ygg\Screen\Layout;
use Ygg\Screen\Repository;

/**
 * Class Export
 * @method Export exportOptions(array $options)
 * @package Ygg\Actions
 */
class Export extends ModalToggle
{
    /**
     * @var string
     */
    protected $view = 'platform::actions.export';

    /**
     * Default attributes value.
     *
     * @var array
     */
    protected $attributes = [
        'class'           => 'btn btn-link',
        'modal'           => 'export-action',
        'method'          => 'export',
        'modalTitle'      => null,
        'icon'            => 'icon-cloud-download',
        'action'          => null,
        'asyncParameters' => null,
        'async'           => false,
        'parameters'      => [],
        'modalLayout'     => [],
        'modalRepository' => null,
        'exportOptions'   => [ 'Xls' => 'XLS', 'Xlsx' => 'XLSX', 'Csv' => 'CSV']
    ];

    /**
     * Create instance of the button.
     *
     * @param string $name
     *
     * @return ModalToggle
     */
    public static function make(string $name = ''): ActionInterface
    {
        if(empty($name)) {
            $name = __('Export');
        }
        return self::buildInstance($name, function () use ($name) {
            $url = url()->current();
            $query = http_build_query($this->get('parameters'));
            $this->buildLayout();

            $action = "{$url}/{$this->get('method')}?{$query}";
            $this->set('action', $action);
        })->parameters(request()->all());
    }

    protected function buildLayout()
    {
        $layout = Layout::modal('export-action', [
            Layout::rows([
                RadioButtons::make('writer_type')
                    ->title(__('File format'))
                    ->options($this->get('exportOptions')),
            ]),
        ])->title(__('Data export'))
            ->rawClick()
            ->applyButton(__('Export'))
            ->closeButton(__('Cancel'));

        $this->attributes['modalRepository'] = new Repository();
        $this->attributes['modalLayout'] = $layout;
    }
}
