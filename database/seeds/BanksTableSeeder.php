<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BanksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('banks')->insert([
            'bank_name' => 'Access Bank',
            'bank_code' => '044'
        ]);

        DB::table('banks')->insert([
            'bank_name' => 'Access Bank (Diamond)',
            'bank_code' => '063'
        ]);

        DB::table('banks')->insert([
            'bank_name' => 'ALAT by WEMA',
            'bank_code' => '035A'
        ]);

        DB::table('banks')->insert([
            'bank_name' => 'ASO Savings and Loans',
            'bank_code' => '401'
        ]);

        DB::table('banks')->insert([
            'bank_name' => 'Citibank Nigeria',
            'bank_code' => '023'
        ]);

        DB::table('banks')->insert([
            'bank_name' => 'Ecobank Nigeria',
            'bank_code' => '050'
        ]);

        DB::table('banks')->insert([
            'bank_name' => 'Ekondo Microfinance Bank',
            'bank_code' => '562'
        ]);

        DB::table('banks')->insert([
            'bank_name' => 'Enterprise Bank',
            'bank_code' => '084'
        ]);

        DB::table('banks')->insert([
            'bank_name' => 'Fidelity Bank',
            'bank_code' => '070'
        ]);

        DB::table('banks')->insert([
            'bank_name' => 'First Bank of Nigeria',
            'bank_code' => '011'
        ]);

        DB::table('banks')->insert([
            'bank_name' => 'First City Monument Bank',
            'bank_code' => '214'
        ]);

        DB::table('banks')->insert([
            'bank_name' => 'Guaranty Trust Bank',
            'bank_code' => '058'
        ]);

        DB::table('banks')->insert([
            'bank_name' => 'Heritage Bank',
            'bank_code' => '030'
        ]);

        DB::table('banks')->insert([
            'bank_name' => 'Jaiz Bank',
            'bank_code' => '301'
        ]);

        DB::table('banks')->insert([
            'bank_name' => 'Keystone Bank',
            'bank_code' => '082'
        ]);

        DB::table('banks')->insert([
            'bank_name' => 'MainStreet Bank',
            'bank_code' => '014'
        ]);

        DB::table('banks')->insert([
            'bank_name' => 'Parallex Bank',
            'bank_code' => '526'
        ]);

        DB::table('banks')->insert([
            'bank_name' => 'Polaris Bank',
            'bank_code' => '076'
        ]);

        DB::table('banks')->insert([
            'bank_name' => 'Providus Bank',
            'bank_code' => '101'
        ]);

        DB::table('banks')->insert([
            'bank_name' => 'Stanbic IBTC Bank',
            'bank_code' => '221'
        ]);

        DB::table('banks')->insert([
            'bank_name' => 'Standard Chartered Bank',
            'bank_code' => '068'
        ]);

        DB::table('banks')->insert([
            'bank_name' => 'Sterling Bank',
            'bank_code' => '232'
        ]);

        DB::table('banks')->insert([
            'bank_name' => 'Suntrust Bank',
            'bank_code' => '100'
        ]);

        DB::table('banks')->insert([
            'bank_name' => 'Union Bank of Nigeria',
            'bank_code' => '032'
        ]);

        DB::table('banks')->insert([
            'bank_name' => 'United Bank For Africa',
            'bank_code' => '033'
        ]);

        DB::table('banks')->insert([
            'bank_name' => 'Unity Bank',
            'bank_code' => '215'
        ]);

        DB::table('banks')->insert([
            'bank_name' => 'Wema Bank',
            'bank_code' => '035'
        ]);

        DB::table('banks')->insert([
            'bank_name' => 'Zenith Bank',
            'bank_code' => '057'
        ]);
    }
}
