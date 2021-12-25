<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Detection;

class DetectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Detection::factory()->count(300)->create();
    }
}
