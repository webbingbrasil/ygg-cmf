<?php

namespace Ygg\Resource\Http\Layouts\Comment;

use Ygg\Screen\Layouts\Table;
use Ygg\Screen\TD;

class CommentListLayout extends Table
{
    /**
     * @var string
     */
    public $target = 'comments';

    /**
     * @return array
     */
    public function columns(): array
    {
        return [
            TD::set('approved', __('Status'))
                ->render(function ($comment) {
                    if ($comment->approved) {
                        return '<i class="icon-check text-success mx-3"></i>';
                    }

                    return '<i class="icon-close text-danger mx-3"></i>';
                }),

            TD::set('content', __('Content'))
                ->render(function ($comment) {
                    return '<a href="'.route('platform.systems.comments.edit',
                            $comment->id).'">'.\Str::limit($comment->content, 70).'</a>';
                }),

            TD::set('resource_id', __('Recording'))
                ->render(function ($comment) {
                    if (!is_null($comment->resource)) {
                        return '<a href="'.route('platform.resource.type.edit', [
                            $comment->resource->type,
                            $comment->resource->id,
                        ]).'"><i class="icon-text-center mx-3"></i></a>';
                    }

                    return '<i class="icon-close mx-3"></i>';
                })
                ->align(TD::ALIGN_CENTER),

            TD::set('author_id', __('Author'))
                ->render(function ($comment) {
                    return '<a href="'.route('platform.systems.users.edit',
                            $comment->author_id).'"><i class="icon-user mx-3"></i></a>';
                })
                ->align(TD::ALIGN_CENTER),

            TD::set('updated_at', __('Last edit'))
                ->render(function ($comment) {
                    return $comment->updated_at;
                }),
        ];
    }
}
