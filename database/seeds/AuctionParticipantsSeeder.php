<?php

use Illuminate\Database\Seeder;
use App\CommercialAuction;
use App\CharityAuction;

class AuctionParticipantsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        $output = new Symfony\Component\Console\Output\ConsoleOutput();

        // seeding commercial auction participants
        $commercial_auctions = CommercialAuction::all();
        foreach ($commercial_auctions as $com_a) {
            // seed the highest bidder first
            $highest_bid = $faker->numberBetween($min = 3, $max = 220) * 100 + $com_a->start_bid;
            do {
                $user_id = App\User::all()->random()->id;
                try {
                    factory(App\AuctionParticipants::class)->create([
                        'user_id' => $user_id,
                        'auction_id' => $com_a->auction_id,
                        'amount' => $highest_bid,
                    ]);
                } catch(\Throwable $e) {
                    $output->writeln("<info>warning-user: Skipping user: ".$user_id."</info>");
                    continue;
                }
                break;
            } while(true);
            
            $com_a->highest_bid_user_id = $user_id;
            $com_a->save();

            // seed everyone else (lower bids)
            for ($i=0; $i < $faker->numberBetween($min = 6, $max = 31); $i++) { 
                do {
                    $user_id = App\User::all()->random()->id;
                    try {
                        factory(App\AuctionParticipants::class)->create([
                            'user_id' => $user_id,
                            'auction_id' => $com_a->auction_id,
                            'amount' => $faker->numberBetween($min = $com_a->start_bid, $max = $highest_bid-1),
                        ]);
                    } catch(\Throwable $e) {
                        $output->writeln("<info>warning-loser: Skipping user: ".$user_id."</info>");
                        continue;
                    }
                    break;
                } while(true);
            }
        }

        // seeding charity auction participants
        $charity_auctions = CharityAuction::all();
        foreach ($charity_auctions as $char_a) {
            $contribution_sum = 0;
            // $output->writeln("ID: ".$char_a->id.", GOAL: ".$char_a->goal);
            do {
                $user_id = App\User::all()->random()->id;
                $contribution = $faker->numberBetween($min = 100, $max = 3000);
                $new_contr_sum = $contribution + $contribution_sum;
                $amount = ($new_contr_sum > $char_a->goal) ? $char_a->goal - $contribution_sum : $contribution;
                // $output->writeln("user: ".$user_id);
                try {
                    factory(App\AuctionParticipants::class)->create([
                        'user_id' => $user_id,
                        'auction_id' => $char_a->auction_id,
                        'amount' => $amount,
                    ]);
                } catch(\Throwable $e) {
                    $output->writeln("<info>warning-contrib: Skipping user: ".$user_id."</info>");
                    continue;
                }
                $contribution_sum += $amount;
                // $output->writeln("amount: ".$amount);
            } while($contribution_sum < $char_a->goal);
            // $output->writeln("contrib: ".$contribution_sum);
        }
    }
}
