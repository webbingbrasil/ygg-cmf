<?php

namespace Ygg\Console;

use Illuminate\Database\Migrations\MigrationCreator;

class UploadsMigrationCreator extends MigrationCreator
{

    /**
     * @param string|null $table
     * @param bool        $create
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function getStub($table, $create)
    {
        return $this->files->get(__DIR__.'/stubs//uploads.stub');
    }
}
