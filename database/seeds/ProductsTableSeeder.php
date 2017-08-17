<?php

use Illuminate\Database\Seeder;
use App\Models\Product;
use Faker\Factory as Faker;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->truncate();
        $faker = Faker::create();
        for($i=0; $i < 500; $i++)
        {
            $product                = new Product();
            $product->name          = $faker->name();
            $product->slug          = $faker->slug(4, true);
            $product->description   = $faker->paragraphs(rand(3,5), true);
            $product->price         = rand(10, 1000);
            $product->image         = '700x400.png';
            $product->save();
        }
    }
}
