<?php

use Illuminate\Database\Seeder;

class CharityAuctionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\CharityAuction::class, 15)->create();
    }
}
