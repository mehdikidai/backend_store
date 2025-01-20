<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(9)->create();

        User::factory()->create([
            'name' => 'mehdi kidai',
            'email' => 'mehdikidai@gmail.com',
        ]);

        Customer::factory(5)->create();

        Category::factory(3)->create();

        Product::factory(10)->create();
    }
}
