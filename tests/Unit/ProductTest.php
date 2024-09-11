<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_returns_all_products()
    {
        Product::factory()->count(3)->create();

        $response = $this->get('/api/v1/products');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            '*' => [
                'name',
                'price',
                'description',
            ]
        ]);
    }

    public function test_returns_a_product_by_id()
    {
        $product = Product::factory()->create();

        $response = $this->get('/api/v1/products/' . $product->id);

        $response->assertStatus(200);

        $response->assertJson([
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'status' => $product->status,
            'stock_quantity' => $product->stock_quantity,
            'description' => $product->description,
            'created_at' => $product->created_at,
            'updated_at' => $product->updated_at,
        ]);
    }

    /** @test */
    public function test_returns_404_when_product_not_found()
    {
        $response = $this->get('/api/v1/products/9999999');

        $response->assertStatus(404);
    }

    public function test_creates_a_product()
    {

        $data = [
            'name' => 'Test Product',
            'price' => 100,
            'status' => 'em estoque',
            'description' => 'This is a test product',
            'stock_quantity' => 50
        ];

        $response = $this->post('/api/v1/products', $data);

        $response->assertStatus(200);

        $this->assertDatabaseHas('products', $data);
    }

    /** @test */
    public function test_fails_to_create_product_with_invalid_data()
    {

        $data = [
            'name' => '',
            'price' => 'invalid',
        ];

        $response = $this->post('/api/v1/products', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'price']);
    }

    public function test_updates_a_product()
    {
        $product = Product::factory()->create();

        $data = [
            'name' => 'Updated Product',
            'price' => 150,
            'status' => 'em reposição',
            'description' => 'Updated description',
            'stock_quantity' => 60
        ];

        $response = $this->put('/api/v1/products/' . $product->id, $data);

        $response->assertStatus(200);

        $this->assertDatabaseHas('products', $data);
    }

    /** @test */
    public function test_fails_to_update_product_with_invalid_data()
    {

        $product = Product::factory()->create();

        $data = [
            'name' => '',
            'price' => 'invalid',
        ];

        $response = $this->put('/api/v1/products/' . $product->id, $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'price']);
    }

    /** @test */
    public function test_returns_404_when_updating_non_existent_product()
    {
        $data = [
            'name' => 'Updated Product',
            'price' => 150,
        ];

        $response = $this->put('/api/v1/products/9999999', $data);

        $response->assertStatus(404);
    }
    public function test_deletes_a_product()
    {
        $product = Product::factory()->create();

        $response = $this->delete('/api/v1/products/' . $product->id);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    /** @test */
    public function test_returns_404_when_deleting_non_existent_product()
    {
        $response = $this->delete('/api/v1/products/9999999');
        $response->assertStatus(404);
    }

    /** @test */
    public function test_prevents_sql_injection_in_product_creation()
    {
        $data = [
            'name' => 'Test Product',
            'price' => '100; DROP TABLE products;',
            'status' => 'em estoque',
            'description' => 'This is a test product',
            'stock_quantity' => 50
        ];
    
        $response = $this->post('/api/v1/products', $data);
    
        $response->assertStatus(422);
    
        $this->assertDatabaseMissing('products', [
            'price' => '100; DROP TABLE products;'
        ]);
    }

    /** @test */
    public function test_prevents_sql_injection_in_product_update()
    {
        $product = Product::factory()->create();

        $data = [
            'name' => 'Updated Product',
            'price' => '150; DROP TABLE products;',
            'status' => 'em reposição',
            'description' => 'Updated description',
            'stock_quantity' => 60
        ];

        $response = $this->put('/api/v1/products/' . $product->id, $data);

        $response->assertStatus(422);

        $this->assertDatabaseMissing('products', [
            'price' => '150; DROP TABLE products;'
        ]);
    }
}
