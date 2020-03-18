<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        
        if(env('local') != 'production') {
            $this->call(CustomersTableSeeder::class);
            $this->call(SupliersTableSeeder::class);
            $this->call(TeamTableSeeder::class);
            $this->call(ProductsTableSeeder::class);
            $this->call(EventTableSeeder::class);
        }
    }
}
