<?php

use Illuminate\Database\Seeder;

class AuctionObjectTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\AuctionObjectType::class, 1)->states('test_obj_type')->create();
        factory(App\AuctionObjectType::class, 15)->create();
    }
}
