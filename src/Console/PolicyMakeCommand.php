<?php

namespace Ygg\Console;

use Illuminate\Foundation\Console\PolicyMakeCommand as Base;

class PolicyMakeCommand extends Base
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'ygg:make:policy';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        return $this->option('model')
            ? __DIR__.'/stubs/policy.stub'
            : __DIR__.'/stubs/policy.plain.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'\Ygg\Policies';
    }
}
