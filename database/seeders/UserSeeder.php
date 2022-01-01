<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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
            'name' => 'Admin',
            'username' => 'aaa_aaa',
            'is_admin' => true,
            'password' => Hash::make('secretpassword')
        ]);

        User::create([
            'name' => 'Admin FFFF',
            'username' => 'werk',
            'is_admin' => true,
            'password' => Hash::make('secretpassword')
        ]);

        //create random user
        User::factory()->count(20)->create();
    }
}
