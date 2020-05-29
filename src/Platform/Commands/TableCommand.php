<?php

namespace Ygg\Platform\Commands;

use Illuminate\Console\GeneratorCommand;
use Ygg\Platform\Dashboard;

class TableCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'ygg:table';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new table layout class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Table';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        return Dashboard::path('resources/stubs/table.stub');
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     *
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'\Ygg\Layouts';
    }
}
