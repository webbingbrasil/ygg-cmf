<?php

namespace Ygg\Database\Resource\Seeds;

use Illuminate\Database\Seeder;
use Ygg\Resource\Models\Resource;

class PagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pages = ['example-page'];
        foreach ($pages as $page) {
            if (Resource::where('type', '=', 'page')->where('slug', '=', $page)->count() === 0) {
                factory(Resource::class)->create(['type' => 'page', 'slug' => $page]);
            }
        }
    }
}
