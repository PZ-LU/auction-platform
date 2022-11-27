<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Offer;
use App\User;

class OfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        // seeding 'offers'
        factory(App\Offer::class, 30)->create();
        
        // seeding 'offers_media'
        $MEDIA_COUNT = 50;
        for ($x = 0; $x <= $MEDIA_COUNT; $x++) {
            $dimX = $faker->numberBetween($min = 300, $max = 512);
            $dimY = $faker->numberBetween($min = 300, $max = 512);
            $offerId = Offer::all()->random()->id;
            $link = 'https://picsum.photos/seed/auction_platform_image_'
                    .$faker->numberBetween($min = 0, $max = $MEDIA_COUNT)
                    .$dimX
                    .'/'
                    .$dimY;
            // insert new media
            DB::table('offers_media')->insert([
                'offer_id' => $offerId,
                'photo_path' => $link,
                'file_name' => md5(time()+rand()).'.jpg',
            ]);
            // set offer preview image
            Offer::find($offerId)->update([
                'preview_image' => $link
            ]);
        }

        // seeding 'favorite_offers'
        $FAVORITE_COUNT = 70;
        for ($x = 0; $x <= $FAVORITE_COUNT; $x++) {
            // insert new favorites
            DB::table('favorite_offers')->insertOrIgnore([
                'user_id' => User::all()->random()->id,
                'offer_id' => Offer::all()->random()->id,
            ]);
        }
    }
}
