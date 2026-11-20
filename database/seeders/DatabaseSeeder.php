<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'nama' => 'uroo',
            'email' => 'uroo@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'Admin',
        ]);

        User::create([
            'nama' => 'petugas',
            'email' => 'petugas@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'Petugas',
        ]);

        User::create([
            'nama' => 'pelanggan',
            'email' => 'pelanggan@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'Pelanggan',
        ]);
    }
}
