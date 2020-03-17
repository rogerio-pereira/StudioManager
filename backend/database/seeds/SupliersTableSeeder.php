<?php

use App\Model\Customer;
use Illuminate\Database\Seeder;

class SupliersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Customer::class, 10)->create();
    }
}
