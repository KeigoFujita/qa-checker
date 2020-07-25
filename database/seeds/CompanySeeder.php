<?php

use App\Call;
use App\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $defaultCompanies = ['Penn Foster', 'CLA', 'HomeLight', 'ISSA', 'Funding Circle', 'Rifco'];

        foreach ($defaultCompanies as $company) {
            Company::create([
                'name' => $company
            ])->calls()->saveMany(factory(Call::class, 25)->make());
        }
    }
}