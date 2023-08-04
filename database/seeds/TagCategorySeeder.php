<?php

use Illuminate\Database\Seeder;

class TagCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\TagCategory::class, 1)->states('test_tag_category')->create();
        factory(App\TagCategory::class, 50)->create();
    }
}
