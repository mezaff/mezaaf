<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MonthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $months = [
            ['name' => 'Januari'],
            ['name' => 'Februari'],
            ['name' => 'Maret'],
            ['name' => 'April'],
            ['name' => 'Mei'],
            ['name' => 'Juni'],
            ['name' => 'Juli'],
            ['name' => 'Augustus'],
            ['name' => 'September'],
            ['name' => 'Oktober'],
            ['name' => 'November'],
            ['name' => 'Desember'],
        ];

        DB::table('month_names')->insert($months);
    }
}
