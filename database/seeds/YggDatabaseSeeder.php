<?php

namespace Ygg\Database\Seeds;

use Illuminate\Database\Seeder;

class YggDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * php artisan db:seed --class="Ygg\Database\Seeds\YggDatabaseSeeder"
     *
     * run another class
     * php artisan db:seed --class="Ygg\Database\Seeds\UsersTableSeeder"
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            //AttachmentsTableSeeder::class,
            //UsersTableSeeder::class,
            //RolesTableSeeder::class,
            //SettingsTableSeeder::class,
        ]);
    }
}
