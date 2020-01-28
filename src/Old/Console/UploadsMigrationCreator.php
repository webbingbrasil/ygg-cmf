<?php

namespace Ygg\Old\Console;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Migrations\MigrationCreator;

class UploadsMigrationCreator extends MigrationCreator
{

    /**
     * @param string|null $table
     * @param bool        $create
     * @return string
     * @throws FileNotFoundException
     */
    protected function getStub($table, $create): string
    {
        return $this->files->get(__DIR__.'/stubs//uploads.stub');
    }
}
