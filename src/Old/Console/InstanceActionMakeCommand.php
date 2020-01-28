<?php

namespace Ygg\Old\Console;

use Illuminate\Console\GeneratorCommand;

class InstanceActionMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'ygg:make:instance:action';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new instance action class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'YggInstanceAction';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        return __DIR__ . '/stubs/instance-action.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'\Ygg\Old\Actions';
    }
}
