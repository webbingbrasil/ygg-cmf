<?php

namespace Ygg\Platform\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Ygg\Platform\Dashboard;

class LinkCommand extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'ygg:link';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a symbolic link from resource ygg';

    /**
     * Execute the console command.
     *
     * @param Dashboard $dashboard
     *
     * @return void
     */
    public function handle(Dashboard $dashboard, Filesystem $filesystem)
    {
        $prefix = public_path('resources');

        $filesystem->ensureDirectoryExists($prefix);

        $dashboard->getPublicDirectory()->each(function ($path, $package) use ($prefix, $filesystem) {
            $package = $prefix.'/'.$package;
            $path = rtrim($path, '/');

            if (! $filesystem->exists($package)) {
                $filesystem->link($path, $package);
                $this->line("The [$package] directory has been linked.");
            }
        });

        $this->info('Links have been created.');
    }
}
