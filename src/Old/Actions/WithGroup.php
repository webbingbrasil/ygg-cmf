<?php

namespace Ygg\Old\Actions;

trait WithGroup
{

    protected $groupIndex = 0;

    /**
     * @return int
     */
    public function getGroupIndex(): int
    {
        return $this->groupIndex;
    }

    /**
     * @param $index
     */
    public function setGroupIndex($index): void
    {
        $this->groupIndex = $index;
    }
}
