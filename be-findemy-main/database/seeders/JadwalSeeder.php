<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JadwalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('jadwals')->insert([
            [
                'user_id' => 1,
                'mata_kuliah_id' => 1,
                'dosen' => 'Dr. Agus',
                'ruangan' => 'A101',
                'hari' => 'Senin',
                'jam_mulai' => '08:00',
                'jam_selesai' => '10:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'mata_kuliah_id' => 2,
                'dosen' => 'Ibu Siti',
                'ruangan' => 'B202',
                'hari' => 'Selasa',
                'jam_mulai' => '10:00',
                'jam_selesai' => '12:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'mata_kuliah_id' => 3,
                'dosen' => 'Bapak Joko',
                'ruangan' => 'C303',
                'hari' => 'Rabu',
                'jam_mulai' => '13:00',
                'jam_selesai' => '15:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
