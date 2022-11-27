<?php

use Illuminate\Database\Seeder;

class OfferTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\OfferTag::class, 90)->create();
    }
}
