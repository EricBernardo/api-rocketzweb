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
        $this->call(StatesSeeder::class);
        $this->call(CitiesSeeder::class);
        $this->call(AdminUserSeeder::class);
        $this->call(RolesSeeder::class);
        $this->call(UserRoleSeeder::class);
    }
}
