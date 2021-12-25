<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //create admin
        User::create([
            'name' => 'Admin BKES',
            'username' => 'admin_bkes',
            'is_admin' => true,
            'password' => bcrypt('secretpassword')
        ]);

        //create random user
        User::factory()->count(20)->create();
    }
}
