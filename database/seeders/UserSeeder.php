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
        //clients
        User::factory()->count(10)->create();

        //managers
        $passwordHash = \Hash::make('qweqweqwe');
        User::factory()
            ->count(3)
            ->sequence(fn($seq) => [
                'name' => 'manager-' . ($seq->index + 1),
                'email' => 'manager-' . ($seq->index + 1) . '@qwe.qwe'
            ])
            ->state([
                'password' => $passwordHash,
                'role_id' => 2
            ])->create();

        //senior manager
        User::factory()->state([
            'name' => 'senior manager',
            'email' => 's_manager@qwe.qwe',
            'password' => $passwordHash,
            'role_id' => 3
        ])->create();

        //admin
        User::factory()->state([
            'name' => 'admin',
            'email' => 'admin@qwe.qwe',
            'password' => $passwordHash,
            'role_id' => 4
        ])->create();
    }
}
