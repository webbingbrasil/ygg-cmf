<?php

namespace Ygg\Database\Resource\Seeds;

use Illuminate\Database\Seeder;
use Orchid\Attachment\Models\Attachmentable;
use Ygg\Resource\Models\Comment;
use Ygg\Resource\Models\Resource;

class ResourcesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Resource::class, 20)->create()->each(function ($p) {
            $p->comments()->saveMany(factory(Comment::class, 2)->create(['resource_id' => $p->id])
                ->each(function ($c) {
                    $c->replies()->saveMany(factory(Comment::class, 1)->make(['resource_id' => $c->post_id, 'parent_id' => $c->id]));
                }));
            factory(Attachmentable::class, 4)->create(['attachmentable_id' => $p->id]);
        });
    }
}
