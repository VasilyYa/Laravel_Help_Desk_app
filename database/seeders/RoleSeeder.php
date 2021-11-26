<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'description' => 'client' # !! client id must be 1 - default id when registering new users
        ]);
        Role::create([
            'description' => 'manager'
        ]);
        Role::create([
            'description' => 'senior manager'
        ]);
        Role::create([
            'description' => 'admin'
        ]);

    }
}
