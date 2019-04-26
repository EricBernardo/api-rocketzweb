<?php

use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = date("Y-m-d H:i:s");

        DB::table("roles")->insert([
            [
                "id"         => 1,
                "name"       => "root",
                "guard_name" => "web",
                "created_at" => $now,
                "updated_at" => $now,
            ], [
                "id"         => 2,
                "name"       => "administrator",
                "guard_name" => "web",
                "created_at" => $now,
                "updated_at" => $now,
            ], [
                "id"         => 3,
                "name"       => "deliveryman",
                "guard_name" => "web",
                "created_at" => $now,
                "updated_at" => $now,
            ], [
                "id"         => 4,
                "name"       => "client",
                "guard_name" => "web",
                "created_at" => $now,
                "updated_at" => $now,
            ],

        ]);
    }
}
