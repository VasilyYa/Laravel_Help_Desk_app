<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Status::create([
            'description' => 'opened'
        ]);
        Status::create([
            'description' => 'wait for answer'
        ]);
        Status::create([
            'description' => 'closed'
        ]);

    }
}
