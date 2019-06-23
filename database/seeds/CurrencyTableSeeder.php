<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('currency')->insert([
            [
                'name' => 'SEK',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'NOK',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'DKK',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'EUR',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'USD',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'GBP',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'CHF',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'JPY',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'CAD',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'AUD',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'NZD',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
