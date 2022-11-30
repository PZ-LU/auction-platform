<?php

use Illuminate\Database\Seeder;

class TopicCommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\TopicComment::class, 200)->create();
    }
}
