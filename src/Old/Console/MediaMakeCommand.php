<?php

namespace Ygg\Old\Console;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Symfony\Component\Console\Input\InputOption;

class MediaMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'ygg:make:media';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create the media model for ygg uploads';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Media model';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        return __DIR__ . '/stubs/media.stub';
    }

    /**
     * @param string $name
     * @return mixed|string
     * @throws FileNotFoundException
     */
    protected function buildClass($name): string
    {
        $replace = [];

        if ($this->option('table')) {
            $replace = $this->replaceTableName($replace);
        } else {
            $replace = $this->removeTableProperty($replace);
        }

        return str_replace(
            array_keys($replace), array_values($replace), parent::buildClass($name)
        );
    }

    /**
     * Build replacements required to set the DB table name
     *
     * @param array $replace
     * @return array
     */
    protected function replaceTableName(array $replace): array
    {
        return array_merge($replace, [
            'DummyTable' => $this->option('table'),
        ]);
    }

    /**
     * Build replacements required to remove the table property
     *
     * @param array $replace
     * @return array
     */
    protected function removeTableProperty(array $replace): array
    {
        return array_merge($replace, [
            "    protected \$table = 'DummyTable';\n" => '',
        ]);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getOptions(): array
    {
        return [
            ['table', 't', InputOption::VALUE_REQUIRED, 'Database table name'],
        ];
    }
}
