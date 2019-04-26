<?php

use Illuminate\Database\Seeder;

/**
 * Class AdminUserSeeder
 */
class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            factory(App\User::class)->create([
                    "name" => "Eric Bernardo",
                    "email" => "eric.bernardo.sousa@gmail.com",
                    "password" => bcrypt(env('ADMIN_PWD', '96265851'))]
            );
        } catch (\Illuminate\Database\QueryException $exception) {

        }
    }
}