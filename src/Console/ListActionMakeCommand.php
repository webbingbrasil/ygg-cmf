<?php

namespace Ygg\Console;

use Illuminate\Console\GeneratorCommand;

class ListActionMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'ygg:make:list:action';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new resource list action class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'YggResourceAction';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        return __DIR__.'/stubs/resource-action.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'\Ygg\Actions';
    }
}
