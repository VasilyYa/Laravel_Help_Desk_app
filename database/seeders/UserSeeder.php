<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->count(10)->create();
        User::factory()->state([
            'name' => 'admin',
            'email' => 'admin@qwe.qwe',
            'password' => \Hash::make('qweqweqwe'),
            'role_id' => 4
        ])->create();
        User::factory()->state([
            'name' => 'senior manager',
            'email' => 's_manager@qwe.qwe',
            'password' => \Hash::make('qweqweqwe'),
            'role_id' => 3
        ])->create();
        User::factory()->state([
            'name' => 'manager',
            'email' => 'manager@qwe.qwe',
            'password' => \Hash::make('qweqweqwe'),
            'role_id' => 2
        ])->create();
    }
}
