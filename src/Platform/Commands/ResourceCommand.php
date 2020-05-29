<?php

namespace Ygg\Platform\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Ygg\Platform\Dashboard;

class ResourceCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'ygg:resource {name} {--single} {--m|menu=Content} {--t|title=} {--d|description=} {--s|slug=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new resource class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Resource';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        $resourceTemplate = 'many-resource';

        if ($this->option('single')) {
            $resourceTemplate = 'single-resource';
        }

        return Dashboard::path('/resources/stubs/' . $resourceTemplate . '.stub');
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
        return $rootNamespace.'\Ygg\Resources';
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildClass($name)
    {
        $stub = parent::buildClass($name);

        return $this->replaceTexts($stub, $name);
    }

    protected function getOption($key, $default = null)
    {
        $value = $default;
        if($this->hasOption($key)) {
            $option = $this->option($key);
            if(!is_null($option)) {
                $value = $option;
            }
        }

        return $value;
    }

    protected function replaceTexts($stub, $name)
    {
        $name = str_replace($this->getNamespace($name).'\\', '', $name);
        $menu = Str::title($this->getOption('menu', 'Content'));
        $title = Str::title($this->getOption('title', $name));
        $description = Str::ucfirst($this->getOption('description', $title));
        $slug = Str::slug($this->getOption('slug', $title));

        $stub = str_replace(['{{ menu }}', '{{menu}}'], $menu, $stub);
        $stub = str_replace(['{{ title }}', '{{title}}'], $title, $stub);
        $stub = str_replace(['{{ description }}', '{{description}}'], $description, $stub);
        $stub = str_replace(['{{ slug }}', '{{slug}}'], $slug, $stub);

        return $stub;
    }
}
