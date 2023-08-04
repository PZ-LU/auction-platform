<?php

use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create valid user for tests
        factory(App\User::class, 1)->states('test_user')->create();
        factory(App\User::class, 1)->states('test_admin')->create();

        // Create administration
        factory(App\User::class, 3)->states('admin')->create();
        factory(App\User::class, 1)->states('super_admin')->create();

        // Populate the system
        factory(App\User::class, 100)->create();
    }
}
