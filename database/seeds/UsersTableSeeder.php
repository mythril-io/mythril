<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Test User for developers contributing to project
        factory(App\User::class, 1)->create(['email' => 'mythril@test.com']);
        factory(App\User::class, 50)->create();
    }
}
