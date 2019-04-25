<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create at least one test user for people working on app
        $this->call(UsersTableSeeder::class);
    }
}
