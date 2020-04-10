<?php

namespace Ygg\Resource\Http\Screens\Comment;

use Ygg\Resource\Http\Layouts\Comment\CommentListLayout;
use Ygg\Resource\Models\Comment;
use Ygg\Screen\Screen;

class CommentListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Comments';
    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'User Comments';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        $comments = Comment::with([
            'resource' => function ($query) {
                $query->select('id', 'type', 'slug');
            },
        ])->latest()
            ->paginate();

        return [
            'comments' => $comments,
        ];
    }

    /**
     * Button commands.
     *
     * @return Link[]
     */
    public function actions(): array
    {
        return [];
    }

    /**
     * Views.
     *
     * @return Layout[]
     */
    public function layout(): array
    {
        return [
            CommentListLayout::class,
        ];
    }
}
