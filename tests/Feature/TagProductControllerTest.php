<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class TagProductControllerTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/api/tags');

        $response->assertStatus(200);
    }
}
