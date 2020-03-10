<?php

namespace Ygg\Platform\Http\Layouts;

use Illuminate\Database\Eloquent\Model;
use Ygg\Screen\Field;
use Ygg\Screen\Fields\Label;
use Ygg\Screen\Fields\Radio;
use Ygg\Screen\Layouts\Rows;
use Ygg\Screen\Repository;
use Ygg\Support\Facades\Dashboard;
use Throwable;

class SearchLayout extends Rows
{
    /**
     * @param Repository $query
     *
     * @return bool
     */
    public function canSee(Repository $query): bool
    {
        return Dashboard::getSearch()->count() > 0;
    }

    /**
     * @throws Throwable
     *
     * @return array
     */
    public function fields(): array
    {
        $searchModel = $this->query->get('model');

        $layouts = Dashboard::getSearch()
            ->map(static function (Model $model) use ($searchModel) {
                $radio = Radio::make('type')
                    ->value(get_class($model))
                    ->horizontal()
                    ->placeholder($model->searchLabel());

                if ($model instanceof $searchModel) {
                    $radio->checked();
                }

                return $radio;
            });

        $layouts->prepend(Label::make('test')->title(__('Choose record type:')));

        return [
            Field::group($layouts->all()),
        ];
    }
}
