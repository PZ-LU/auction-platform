<?php

use Illuminate\Database\Seeder;

class TopicCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\TopicCategory::class, 10)->create();
    }
}
