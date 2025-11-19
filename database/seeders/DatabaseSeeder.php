<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            BarangSeeder::class,
            BarangMasukSeeder::class,
            BarangRusakSeeder::class,]);
            PenitipanSeeder::class,
            PenitipanDetailSeeder::class

            TransaksiSeeder::class,
        ]);
    }
}
