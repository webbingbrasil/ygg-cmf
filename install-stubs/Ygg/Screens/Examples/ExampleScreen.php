<?php

namespace App\Ygg\Screens\Examples;

use App\Ygg\Layouts\Examples\ChartBarExample;
use App\Ygg\Layouts\Examples\MetricsExample;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Ygg\Actions\Action;
use Ygg\Actions\Button;
use Ygg\Actions\DropDown;
use Ygg\Actions\ModalToggle;
use Ygg\Screen\Fields\Input;
use Ygg\Screen\Layout;
use Ygg\Screen\Repository;
use Ygg\Screen\Screen;
use Ygg\Screen\TD;
use Ygg\Support\Facades\Toast;

class ExampleScreen extends Screen
{
    /**
     * Fish text for the table.
     */
    public const TEXT_EXAMPLE = 'Lorem ipsum at sed ad fusce faucibus primis, potenti inceptos ad taciti nisi tristique
    urna etiam, primis ut lacus habitasse malesuada ut. Lectus aptent malesuada mattis ut etiam fusce nec sed viverra,
    semper mattis viverra malesuada quam metus vulputate torquent magna, lobortis nec nostra nibh sollicitudin
    erat in luctus.';

    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Example screen';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Sample Screen Components';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'charts' => [
                [
                    'name' => 'Some Data',
                    'values' => [25, 40, 30, 35, 8, 52, 17],
                ],
                [
                    'name' => 'Another Set',
                    'values' => [25, 50, -10, 15, 18, 32, 27],
                ],
                [
                    'name' => 'Yet Another',
                    'values' => [15, 20, -3, -15, 58, 12, -17],
                ],
                [
                    'name' => 'And Last',
                    'values' => [10, 33, -8, -3, 70, 20, -34],
                ],
            ],
            'table' => [
                new Repository(['id' => 100, 'name' => self::TEXT_EXAMPLE, 'price' => 10.24, 'created_at' => '01.01.2020']),
                new Repository(['id' => 200, 'name' => self::TEXT_EXAMPLE, 'price' => 65.9, 'created_at' => '01.01.2020']),
                new Repository(['id' => 300, 'name' => self::TEXT_EXAMPLE, 'price' => 754.2, 'created_at' => '01.01.2020']),
                new Repository(['id' => 400, 'name' => self::TEXT_EXAMPLE, 'price' => 0.1, 'created_at' => '01.01.2020']),
                new Repository(['id' => 500, 'name' => self::TEXT_EXAMPLE, 'price' => 0.15, 'created_at' => '01.01.2020']),

            ],
            'metrics' => [
                ['keyValue' => number_format(6851, 0), 'keyDiff' => 10.08],
                ['keyValue' => number_format(24668, 0), 'keyDiff' => -30.76],
                ['keyValue' => number_format(65661, 2), 'keyDiff' => 3.84],
                ['keyValue' => number_format(10000, 0), 'keyDiff' => -169.54],
                ['keyValue' => number_format(1454887.12, 2), 'keyDiff' => 0.2],
            ],
        ];
    }

    /**
     * Button commands.
     *
     * @return Action[]
     */
    public function actions(): array
    {
        return [

            Button::make('Show toast')
                ->method('showToast')
                ->novalidate()
                ->icon('icon-bag'),

            ModalToggle::make('Launch demo modal')
                ->modal('exampleModal')
                ->method('showToast')
                ->icon('icon-full-screen'),

            DropDown::make('Dropdown button')
                ->icon('icon-folder-alt')
                ->list([

                    Button::make('Action')
                        ->method('showToast')
                        ->icon('icon-bag'),

                    Button::make('Another action')
                        ->method('showToast')
                        ->icon('icon-bubbles'),

                    Button::make('Something else here')
                        ->method('showToast')
                        ->icon('icon-bulb'),
                ]),

        ];
    }

    /**
     * Views.
     *
     * @return array
     * @throws \Throwable
     *
     */
    public function layout(): array
    {
        return [
            MetricsExample::class,
            ChartBarExample::class,

            Layout::table('table', [
                TD::set('id', 'ID')
                    ->width('150')
                    ->render(function (Repository $model) {
                        // Please use view('path')
                        return "<img src='https://picsum.photos/450/200?random={$model->get('id')}'
                              alt='sample'
                              class='mw-100 d-block img-fluid'>
                            <span class='small text-muted mt-1 mb-0'># {$model->get('id')}</span>";
                    }),

                TD::set('name', 'Name')
                    ->width('450')
                    ->render(function (Repository $model) {
                        return Str::limit($model->get('name'), 200);
                    }),

                TD::set('price', 'Price')
                    ->render(function (Repository $model) {
                        return '$ ' . number_format($model->get('price'), 2);
                    }),

                TD::set('created_at', 'Created'),
            ]),

            Layout::modal('exampleModal', [
                Layout::rows([
                    Input::make('toast')
                        ->title('Messages to display')
                        ->placeholder('Hello word!')
                        ->required(),
                ]),
            ])->title('Create your own toast message'),
        ];
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function showToast(Request $request)
    {
        Toast::warning($request->get('toast', 'Hello, world! This is a toast message.'));

        return back();
    }
}
