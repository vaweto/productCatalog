<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
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

        foreach ($response['data'] as $data) {
            $this->assertEquals($category->id, $data['category']['id']);
        }

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

    public function test_you_can_retrieve_a_product_page(): void
    {
        $category = Category::factory()->create();

        $categoryProduct = Product::factory()->create(['category_id' => $category->id]);

        $this->json('get', 'api/products/' . $categoryProduct->id)
            ->assertStatus(200)
            ->assertSee($category->code);
    }

    public function test_you_cannot_retrieve_a_product_page_that_is_missing(): void
    {
        $category = Category::factory()->create();

        Product::factory()->create(['category_id' => $category->id]);

        $response = $this->json('get', 'api/products/' . 99999999999999999999999999)
            ->assertStatus(404);
    }

    public function test_you_can_create_a_product(): void
    {
        $category = Category::factory()->create();

        $product = Product::factory()->make(['category_id' => $category->id, 'name' => 'test vag']);

        $this->json('post', 'api/products', $product->toArray())
            ->assertStatus(201);

        $this->assertDatabaseHas('products', ['name' => $product->name, 'code' => $product->code]);
    }

    public function test_you_can_create_a_product_with_category_id(): void
    {
        $category = Category::factory()->create();

        $product = Product::factory()->make(['category_id' => $category->id, 'name' => 'test vag']);

        $this->json('post', 'api/products', $product->toArray())
            ->assertStatus(201);

        $this->assertDatabaseHas('products', ['name' => $product->name, 'code' => $product->code, 'category_id' => $category->id]);
    }

    public function test_you_can_create_a_product_with_tags(): void
    {
        $tags = Tag::factory(5)->create();

        $product = Product::factory()->make(['name' => 'test vag']);

        $this->json('post', 'api/products', [...$product->toArray(), 'tags' => $tags->pluck('id')])
            ->assertStatus(201);

        $this->assertDatabaseHas('products', ['name' => $product->name, 'code' => $product->code]);

        $productDB = Product::where('code', $product->code)->first();
        $this->assertDatabaseHas('taggables', ['tag_id' => $tags->get(1)->id, 'taggable_id' => $productDB->id]);
        $this->assertDatabaseHas('taggables', ['tag_id' => $tags->get(2)->id, 'taggable_id' => $productDB->id]);
    }

    public function test_create_product_name_with_numbers_fails(): void
    {
        $category = Category::factory()->create();

        $product = Product::factory()->make(['category_id' => $category->id, 'name' => 'test12']);

        $this->json('post', 'api/products', $product->toArray())
            ->assertStatus(422);

        $this->assertDatabaseMissing('products', ['name' => $product->name, 'code' => $product->code]);
    }

    public function test_create_product_name_with_symbols_fails(): void
    {
        $category = Category::factory()->create();

        $product = Product::factory()->make(['category_id' => $category->id, 'name' => 'test$']);

        $this->json('post', 'api/products', $product->toArray())
            ->assertStatus(422);

        $this->assertDatabaseMissing('products', ['name' => $product->name, 'code' => $product->code]);
    }

    public function test_create_product_code_with_spaces_fails(): void
    {
        $category = Category::factory()->create();

        $product = Product::factory()->make(['category_id' => $category->id, 'name' => 'vagelis', 'code' => 'test test']);

        $this->json('post', 'api/products', $product->toArray())
            ->assertStatus(422);

        $this->assertDatabaseMissing('products', ['name' => $product->name, 'code' => $product->code]);
    }

    public function test_create_product_code_with_Uppercase_fails(): void
    {
        $category = Category::factory()->create();

        $product = Product::factory()->make(['category_id' => $category->id, 'name' => 'vagelis', 'code' => 'Test']);

        $this->json('post', 'api/products', $product->toArray())
            ->assertStatus(422);

        $this->assertDatabaseMissing('products', ['name' => $product->name, 'code' => $product->code]);
    }

    public function test_you_can_update_a_product(): void
    {
        $this->withExceptionHandling();
        $category = Category::factory()->create();

        $product = Product::factory()->create(['category_id' => $category->id, 'name' => 'test vag']);

        $response = $this->json('put', 'api/products/'. $product->id, ['name' => 'hello'])
            ->assertStatus(200);

        $this->assertDatabaseHas('products', ['name' => 'hello', 'code' => $product->code]);
    }


    public function test_you_can_update_a_product_tags_by_delete_all_the_previous(): void
    {
        $tags = Tag::factory(5)->create();
        $category = Category::factory()->create();

        $product = Product::factory()->create(['category_id' => $category->id, 'name' => 'test vag']);
        $product->tags()->saveMany($tags);

        $newTags = Tag::factory(5)->create();

        $response = $this->json('put', 'api/products/'. $product->id, ['tags' => $newTags->pluck('id')])
            ->assertStatus(200);

        $this->assertDatabaseHas('taggables', ['tag_id' => $newTags->get(1)->id, 'taggable_id' => $product->id]);
        $this->assertDatabaseHas('taggables', ['tag_id' => $newTags->get(2)->id, 'taggable_id' => $product->id]);
        $this->assertDatabaseMissing('taggables', ['tag_id' => $tags->get(2)->id, 'taggable_id' => $product->id]);
    }

    public function test_you_can_delete_a_product(): void
    {
        $tags = Tag::factory(5)->create();
        $category = Category::factory()->create();

        $product = Product::factory()->create(['category_id' => $category->id, 'name' => 'test vag']);
        $product->tags()->saveMany($tags);

        $this->json('delete', 'api/products/'. $product->id)
            ->assertStatus(200);

        $this->assertDatabaseMissing('products', ['name' => 'hello', 'code' => $product->code]);

    }
}
