<?php

namespace Ygg\Console;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Composer;

/**
 * Class MediaMigrationMakeCommand
 * @package Ygg\Form\Eloquent\Uploads\Migration
 */
class MediaMigrationMakeCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'ygg:make:media-migration {table_name}';

    /**
     * @var string
     */
    protected $description = 'Creates the ygg uploads table migration file.';

    /**
     * @var UploadsMigrationCreator
     */
    protected $creator;

    /**
     * @var Composer
     */
    protected $composer;

    /**
     * Create a new migration install command instance.
     *
     * @param UploadsMigrationCreator $creator
     * @param Composer                $composer
     */
    public function __construct(UploadsMigrationCreator $creator, Composer $composer)
    {
        parent::__construct();

        $this->creator = $creator;
        $this->composer = $composer;
    }

    /**
     * @throws Exception
     */
    public function handle(): void
    {
        $table = trim($this->input->getArgument('table_name'));
        $name = "create_{$table}_table";

        $this->writeMigration($name, $table);

        $this->composer->dumpAutoloads();
    }

    /**
     * @param $name
     * @param $table
     * @throws Exception
     */
    protected function writeMigration($name, $table): void
    {
        $file = pathinfo($this->creator->create(
            $name, $this->getMigrationPath(), $table, true
        ), PATHINFO_FILENAME);

        $this->line("<info>Created Migration:</info> {$file}");
    }

    /**
     * Get the path to the migration directory.
     *
     * @return string
     */
    protected function getMigrationPath(): string
    {
        return $this->laravel->databasePath().DIRECTORY_SEPARATOR.'migrations';
    }
}
