<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Ambil user pertama (misalnya admin@example.com) untuk mengisi kolom user_id
        $user = User::first();

        if (!$user) {
            $this->command->warn('Tabel users masih kosong! Jalankan UserSeeder terlebih dahulu.');
            return;
        }

        $categories = [
            [
                'nama' => 'Makanan',
                'deskripsi' => 'Berbagai macam hidangan utama dan makanan berat',
            ],
            [
                'nama' => 'Minuman',
                'deskripsi' => 'Aneka minuman segar, kopi, teh, dan jus',
            ],
            [
                'nama' => 'Dessert',
                'deskripsi' => 'Hidangan penutup, kue, es krim, dan makanan manis',
            ],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['nama' => $category['nama']],
                [
                    'user_id' => $user->id,
                    'deskripsi' => $category['deskripsi'],
                ]
            );
        }
    }
}