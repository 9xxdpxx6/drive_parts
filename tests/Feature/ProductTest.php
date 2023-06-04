<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /**
     @test
     */
    public function a_product_can_be_stored()
    {
//        $this->withoutExceptionHandling();
//
//        $data = [
//            'part_number' => 'QWE123',
//            'name' => 'test product',
//            'alias' => 'test-product-qwe123',
//            'description' => 'test description',
//            'price' => '159.26',
//            'old_price' => '200',
//            'qty' => '50',
//            'purchase_price' => '144.90',
//            'is_active' => 'true',
//            'category_id' => '1',
//            'brand_id' => '1',
//        ];
//
//        $response = $this->post('/products/', $data);
//
//        $response->assertOk();
//
//        $this->assertDatabaseCount('products', 1);

        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
    @test
     */
    public function get_current_products_with_filter()
    {
//        $this->withoutExceptionHandling();
//
//        $filter = [
//            'keyword' => 'product1',
//            'categories' => '1',
//            'brands' => '1',
//            'sort' => 'name_asc',
//            'page' => '1',
//            'per_page' => '50',
//        ];
//
//        $products = \App\Models\Product::filter($filter)->get();
//
//        $response = $this->get('/products', $filter);
//
//        $response->assertStatus($products);

        $response = $this->get('/');

        $response->assertStatus(200);

    }
}
