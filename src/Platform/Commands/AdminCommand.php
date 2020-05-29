<?php

namespace Ygg\Platform\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Ygg\Platform\Dashboard;
use Ygg\Platform\Models\User;

class AdminCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'ygg:admin';

    /**
     * @var string
     */
    protected $signature = 'ygg:admin {name?} {email?} {password?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create user administrator';

    /**
     * Permissions.
     *
     * @var Collection
     */
    protected $permissions;

    /**
     * CreateAdminCommand constructor.
     *
     * @param Dashboard $dashboard
     */
    public function __construct(Dashboard $dashboard)
    {
        parent::__construct();

        $this->permissions = $dashboard->getPermission()->collapse();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            Dashboard::modelClass(User::class)
                ->createAdmin(
                    $this->argument('name') ?? $this->ask('What is your name?', 'admin'),
                    $this->argument('email') ?? $this->ask('What is your email?', 'admin@admin.com'),
                    $this->argument('password') ?? $this->secret('What is the password?')
                );

            $this->info('User created successfully.');
        } catch (Exception | QueryException $e) {
            $this->error($e->getMessage());
        }
    }
}
