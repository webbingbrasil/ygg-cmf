<?php

namespace Ygg\Console;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Symfony\Component\Console\Input\InputOption;

class FilterMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'ygg:make:filter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new filter class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Filter';

    /**
     * @param string $name
     * @return mixed|string
     * @throws FileNotFoundException
     */
    protected function buildClass($name)
    {
        $replace = [];
        if (!$this->option('multiple')) {
            $replace = $this->removeMultipleInterface($replace);
        }

        return str_replace(
            array_keys($replace), array_values($replace), parent::buildClass($name)
        );
    }

    /**
     * Build replacements required to remove the ResourceListMultipleFilter interface
     *
     * @param array $replace
     * @return array
     */
    protected function removeMultipleInterface(array $replace): array
    {
        return array_merge($replace, [
            "use Ygg\Filters\MultipleFilter;\n" => '',
            'MultipleFilter,' => '',
            'MultipleFilter' => '',
        ]);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        return $this->option('required')
            ? __DIR__.'/stubs/filter.required.stub'
            : __DIR__.'/stubs/filter.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'\Ygg\Filters';
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getOptions(): array
    {
        return [
            ['required', 'r', InputOption::VALUE_NONE, 'Create a filter whoes value cannot be null'],
            ['multiple', 'm', InputOption::VALUE_NONE, 'Create a filter that can have multiple values'],
        ];
    }
}
