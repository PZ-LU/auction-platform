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
        factory(App\User::class, 30)->create();
        factory(App\User::class, 3)->states('admin')->create();
        factory(App\User::class, 1)->states('super_admin')->create();
    }
}
