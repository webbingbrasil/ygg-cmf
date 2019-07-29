<?php

namespace Ygg\Console;

use Illuminate\Console\GeneratorCommand;

class DashboardMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'ygg:make:dashboard:action';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new dashboard action';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Dashboard';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/dashboard.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Ygg';
    }
}
