<?php

namespace Ygg\Platform\Events;

use Illuminate\Console\Command;

/**
 * Class InstallEvent.
 */
class InstallEvent
{
    /**
     * @var Command
     */
    public $command;

    /**
     * InstallEvent constructor.
     *
     * @param Command $command
     */
    public function __construct(Command $command)
    {
        $this->command = $command;
    }
}
