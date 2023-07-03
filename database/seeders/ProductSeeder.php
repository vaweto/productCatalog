<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = Tag::factory(20)->create();

        Category::factory(50)->create()->each(function ($category) use ($tags) {
            Product::factory(20)->create([
                'category_id' => $category->id,
            ])->each(function ($product) use ($tags) {
                $product->tags()->saveMany($tags->random(5));
            });
        });
    }
}
