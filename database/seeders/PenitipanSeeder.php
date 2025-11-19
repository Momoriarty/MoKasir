<?php
namespace Database\Seeders;

use App\Models\Penitipan;
use Illuminate\Database\Seeder;

class PenitipanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Penitipan::create([
            'nama_penitip' => 'Penitipan A',
            'tanggal_titip' => '2024-01-15',
        ]);

        Penitipan::create([
            'nama_penitip' => 'Penitipan B',
            'tanggal_titip' => '2024-01-16',
        ]);
    }
}
