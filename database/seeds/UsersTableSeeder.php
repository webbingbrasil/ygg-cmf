<?php

namespace Ygg\Database\Seeds;

use Illuminate\Database\Seeder;
use Ygg\Platform\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class, 10)->create();
    }
}
