<?php

use App\Model\Suplier;
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
        factory(Suplier::class, 10)->create();
    }
}
