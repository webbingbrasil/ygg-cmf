<?php

namespace Ygg\Old\Console;

use Illuminate\Console\GeneratorCommand;

class DashboardMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'ygg:make:dashboard';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new dashboard';

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
    protected function getStub(): string
    {
        return __DIR__ . '/stubs/dashboard.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'\Ygg';
    }
}
