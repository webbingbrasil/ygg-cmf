<?php

namespace Ygg\Platform\Commands;

use Illuminate\Console\GeneratorCommand;
use Ygg\Platform\Dashboard;

class MetricsCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'ygg:metrics';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new metrics class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Metric';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        return Dashboard::path('resources/stubs/metrics.stub');
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
