<?php

namespace Ygg\Database\Seeds;

use Illuminate\Database\Seeder;
use Ygg\Platform\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Role::class, 5)->create();
    }
}
