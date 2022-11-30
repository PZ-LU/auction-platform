<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersSeeder::class);
        $this->call(TagCategorySeeder::class);
        $this->call(OfferSeeder::class);
        $this->call(OfferTagSeeder::class);
        $this->call(TopicCategorySeeder::class);
        $this->call(TopicSeeder::class);
        $this->call(TopicCommentSeeder::class);
        $this->call(AuctionObjectTypeSeeder::class);
        $this->call(CharityAuctionSeeder::class);
        $this->call(CommercialAuctionSeeder::class);
        $this->call(AuctionParticipantsSeeder::class);
    }
}
