<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public $seeders = [
        RoleSeeder::class,
        UserSeeder::class,

    ];

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->seeders as $seeder) {

            $this->call($seeder);

        }
    }
}
