<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MataKuliahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('mata_kuliahs')->insert([
            [
                'nama' => 'Matematika Diskrit',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Pemrograman Web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Basis Data',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Sistem Operasi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
