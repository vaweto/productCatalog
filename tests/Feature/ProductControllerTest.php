<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    public function test_product_index_endpoint_that_include_latest_product(): void
    {
        Product::factory()->create(['name' => 'vagelisTest']);

        $response = $this->json('get', 'api/products')
            ->assertStatus(200);

        $this->assertEquals($response['data'][0]['name'], 'vagelisTest');
    }

    public function test_product_index_filtered_by_category(): void
    {
        $category = Category::factory()->create();

        $categoryProducts = Product::factory(10)->create(['category_id' => $category->id]);

        //other category product
        $otherCategoryProduct = Product::factory()->create(['name' => 'vagelisTest']);

        $response = $this->json('get', 'api/products?category='. $category->id)
            ->assertStatus(200);

        $categoryProducts->each(function ($product, $key) use ($response) {
            $this->assertContains($product->name, $response['data'][$key]);
        });

        $this->assertStringNotContainsString($otherCategoryProduct->code, json_encode($response['data']));
    }

    public function test_product_index_filtered_by_category_return_empty_if_nothing_found(): void
    {
        $category = Category::factory()->create();
        $anotherCategory = Category::factory()->create();

        $categoryProducts = Product::factory(10)->create(['category_id' => $category->id]);

        //other category product
        $otherCategoryProduct = Product::factory()->create(['name' => 'vagelisTest']);

        $response = $this->json('get', 'api/products?category='. $anotherCategory->id)
            ->assertStatus(200);

        $this->assertEmpty($response['data']);
    }

    public function test_product_return_200_for_wrong_filter(): void
    {
        $category = Category::factory()->create();

        $categoryProducts = Product::factory(10)->create(['category_id' => $category->id]);

        //other category product
        $otherCategoryProduct = Product::factory()->create(['name' => 'vagelisTest']);

        $response = $this->json('get', 'api/products?test=test')
            ->assertStatus(200);

        $categoryProducts->each(function ($product) use ($response) {
            $this->assertStringContainsString($product->code, json_encode($response['data']));
        });

        $this->assertStringContainsString($otherCategoryProduct->code, json_encode($response['data']));
    }
}
