<?php

namespace Ygg\Resource\Http\Layouts\Comment;

use Ygg\Screen\Fields\CheckBox;
use Ygg\Screen\Fields\TextArea;
use Ygg\Screen\Layouts\Rows;

class CommentEditLayout extends Rows
{
    /**
     * Views.
     *
     * @throws \Throwable
     *
     * @return array
     */
    public function fields(): array
    {
        return [
            TextArea::make('comment.content')
                ->max(255)
                ->rows(10)
                ->required()
                ->title(__('Content'))
                ->help(__('User comment')),

            CheckBox::make('comment.approved')
                ->title(__('Checking'))
                ->help(__('Show comment'))
                ->sendTrueOrFalse(),
        ];
    }
}
