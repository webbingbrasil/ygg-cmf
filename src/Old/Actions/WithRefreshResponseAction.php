<?php

namespace Ygg\Old\Actions;

trait WithRefreshResponseAction
{
    /**
     * @param $ids
     * @return array
     */
    protected function refresh($ids): array
    {
        return [
            'action' => 'refresh',
            'items' => (array)$ids
        ];
    }
}