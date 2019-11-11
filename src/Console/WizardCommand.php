<?php

namespace Ygg\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class WizardCommand
 * @package Ygg\Console
 */
class WizardCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'ygg:wizard';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a complete CRUD';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $config = collect();
        // Configure resource name and paths
        $inputModelClass = $this->option('model') ?? $this->ask('Model class name');
        $fullModelClass = $this->parseClassname($inputModelClass);
        $resourceName = class_basename($fullModelClass);
        $pluralResourceName = Str::plural($resourceName);
        $resourceNamespace = $this->qualifyClass($pluralResourceName . '/' . $resourceName);

        // create resource model
        $this->call('make:model', ['name' => $fullModelClass]);

        $this
            ->generate('list', 'ygg:make:list', $resourceNamespace, $config, ['--model' => $inputModelClass])
            ->generate('form', 'ygg:make:form', $resourceNamespace, $config, ['--model' => $inputModelClass])
            ->generate('policy', 'ygg:make:policy', $resourceNamespace, $config, ['--model' => $inputModelClass])
            ->generate('validator', 'ygg:make:validator', $resourceNamespace, $config);

        $this->info('Wizard complete!');
        $this->line('Add this to resources in `config/ygg.php`:');
        $configString = $config->collapse()->map(static function ($class, $key) {
            return "'{$key}' => {$class},";
        })->implode("\n            ");

        $this->comment("        '{$resourceName}' => [
            {$configString}
        ],");
    }

    /**
     * Parse the class name and format according to the root namespace.
     *
     * @param  string  $name
     * @return string
     */
    protected function qualifyClass($name): string
    {
        $name = ltrim($name, '\\/');

        $rootNamespace = $this->rootNamespace();

        if (Str::startsWith($name, $rootNamespace)) {
            return $name;
        }

        $name = str_replace('/', '\\', $name);

        return $this->qualifyClass(
            $this->getDefaultNamespace(trim($rootNamespace, '\\')).'\\'.$name
        );
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

    /**
     * Get the root namespace for the class.
     *
     * @return string
     */
    protected function rootNamespace(): string
    {
        return $this->laravel->getNamespace();
    }


    /**
     * @param string     $option
     * @param string     $command
     * @param string     $resourceNamespace
     * @param Collection $config
     * @param array      $parameters
     * @return WizardCommand
     */
    protected function generate(string $option, string $command, string $resourceNamespace, Collection $config, array $parameters = []): self
    {
        if ($this->option($option) || $this->confirm('Would you like to generate a ' . $option . ' class for this model?')) {
            $studlyOption = Str::studly($option);
            $class = $this->ask($studlyOption . ' class name', $resourceNamespace.$studlyOption);
            $this->call($command, array_merge(['name' => $class], $parameters));
            $config->push([$option => '\\'.$this->parseClassname($class).'::class']);
        }

        return $this;
    }

    /**
     * Get the fully-qualified class name.
     *
     * @param string $class
     * @return string
     */
    protected function parseClassname(string $class): string
    {
        if (preg_match('([^A-Za-z0-9_/\\\\])', $class)) {
            throw new InvalidArgumentException('Class name contains invalid characters.');
        }

        $rootNamespace = $this->laravel->getNamespace();
        $class = trim(str_replace('/', '\\', $class), '\\');

        if (!Str::startsWith($class, $rootNamespace)) {
            $class = $rootNamespace.$class;
        }

        return $class;
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getOptions(): array
    {
        return [
            ['model', 'm', InputOption::VALUE_REQUIRED, 'The model that the list displays'],
            ['list', null, InputOption::VALUE_NONE, 'Create a list for the model'],
            ['form', null, InputOption::VALUE_NONE, 'Create a form for the model'],
            ['policy', null, InputOption::VALUE_NONE, 'Create a policy for the model'],
            ['validator', null, InputOption::VALUE_NONE, 'Create a validator for the model'],
        ];
    }
}
