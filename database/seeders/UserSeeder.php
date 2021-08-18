<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
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
        User::create([
            'email' => 'admin@example.com',
            'password' => Hash::make('admin'),
            'first_name' => 'Jack',
            'last_name' => 'Williams',
            'phone' => '920-268-6431',
            'address' => '3287 Hartland Avenue',
            'city' => 'Green Bay',
            'zip_code' => '54301',
            'country' => 'Wisconsin',
            'state' => 'Wisconsin',
            'birthday' => date('Y-m-d')
        ]);

        User::create([
            'email' => 'user@example.com',
            'password' => Hash::make('user'),
            'first_name' => 'Dave',
            'last_name' => 'Johnson',
            'phone' => '920-268-6432',
            'address' => '3287 Hartland Avenue',
            'city' => 'Green Bay',
            'zip_code' => '54301',
            'country' => 'Wisconsin',
            'state' => 'Wisconsin',
            'birthday' => date('Y-m-d')
        ]);
    }
}
