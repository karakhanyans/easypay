<?php

use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currencies = [
            ['name' => 'AMD'],
            ['name' => 'EUR'],
            ['name' => 'RUB'],
            ['name' => 'GBR'],
        ];
        \App\Models\Currency::insert($currencies);
        \App\Models\Currency::all()->each(function($currency){
            $currency->rates()->create([
                'value' => mt_rand() / mt_getrandmax(),
                'date' => date('Y-m-d')
            ]);
        });
    }
}
