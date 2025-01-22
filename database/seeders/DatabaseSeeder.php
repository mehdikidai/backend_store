<?php

namespace Database\Seeders;

use App\Enum\Roles;
use App\Models\Category;
use App\Models\Color;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Role;
use App\Models\Size;
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

        Role::factory(3)->create();

        User::factory(9)->create()->each(function ($user): void {
            $roleId = Role::where('name', Roles::Customer->value)->pluck('id')->first();
            $user->roles()->attach([$roleId]);
        });

        User::factory()->create([
            'name' => 'mehdi kidai',
            'email' => 'mehdikidai@gmail.com',
        ])->roles()->attach(Role::all()->pluck('id'));

        Customer::factory(5)->create();

        Category::factory(3)->create();

        $colors = Color::factory(4)->create();

        $sizes = Size::factory(5)->create();

        Product::factory(10)->create()->each(function ($product) use ($colors, $sizes): void {
            $product->colors()->attach($colors->pluck('id')->toArray());
            $product->sizes()->attach($sizes->pluck('id')->toArray());
        });

    }
}
