<?php

namespace Ygg\Database\Resource\Seeds;

use Illuminate\Database\Seeder;
use Ygg\Resource\Models\Taxonomy;
use Ygg\Resource\Models\Term;

class TermsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Term::class, 20)->create()->each(function ($u) {
            $u->taxonomy()->saveMany(factory(Taxonomy::class, 1)->make());
        });
    }
}
