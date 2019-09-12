<?php

use App\Category;
use App\Product;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DBSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Category::class, 5)
            ->create()
        ->each(function ($category) {
            $category->childrenCategories()->saveMany(factory(App\Category::class, rand(2, 3))->make());
    });

        Category::where('parent_id', '<>', null)->get()->each(function ($category) {
            $category->childrenCategories()->saveMany(factory(App\Category::class, rand(2, 3))->make());
        });

        Category::all()->each(function ($category) {
            $category->products()->attach(factory(App\Product::class, rand(1, 3))->create());
        });

        $categories = Category::offset(20)->limit(3)->get();

        $prod = Product::offset(20)->limit(2)->get();

        $categories->each(function ($category) use ($prod) {
            $category->products()->attach($prod);
        });

        User::create([
            'name' => "user1",
            'email' => "user@ex.com",
            'password' => Hash::make('pass'),
            'api_token' => 'qwertyqwerty',
        ]);

    }
}
