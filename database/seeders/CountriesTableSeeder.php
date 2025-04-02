<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Path ke file CSV
        $csvFile = database_path('countries.csv');

        // Baca file CSV
        $file = fopen($csvFile, 'r');

        // Lewati baris header
        $header = fgetcsv($file);

        // Loop melalui setiap baris data
        while (($row = fgetcsv($file)) !== false) {
            DB::table('countries')->insert([
                'phone_code' => $row[0], // Kolom `id`
                'name' => $row[1], // Kolom `name`
                'country_code' => $row[2], // Kolom `code`
                // 'created_at' => now(),
                // 'updated_at' => now(),
            ]);
        }

        // Tutup file CSV
        fclose($file);
    }
}
