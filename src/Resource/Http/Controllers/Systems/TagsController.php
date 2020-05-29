<?php

namespace Ygg\Resource\Http\Controllers\Systems;

use Ygg\Platform\Http\Controllers\Controller;
use Ygg\Resource\Models\Tag;

/**
 * Class TagsController.
 */
class TagsController extends Controller
{
    /**
     * @param string $tag
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $tag)
    {
        $tags = Tag::latest('count')
            ->where('name', 'like', '%'.$tag.'%')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return [
                    'id'    => $item['name'],
                    'text'  => $item['name'],
                    'count' => $item['count'],
                ];
            })
            ->toArray();

        return response()->json($tags);
    }
}
