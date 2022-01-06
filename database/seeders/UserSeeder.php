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
            'username' => 'admin_bkes',
            'is_admin' => true,
            'password' => Hash::make('password')
        ]);
        //create random user
        User::factory()->count(20)->create();
    }
}
