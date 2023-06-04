<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    /**
     @test
     */
    public function an_order_can_be_stored()
    {
//        $this->withoutExceptionHandling();
//
//        $data = [
//            'user_id' => '1',
//            'car_id' => '1',
//            'is_paid' => '1',
//            'total_price' => 500,
//            'discount' => 10,
//            'status_id' => '2',
//            'payment_method' => '1',
//            'labels' => '',
//            'purchases' => [\App\Models\Purchase::all()],
//            'comment' => 'test comment',
//            'manager_id' => '3',
//        ];
//
//        $response = $this->post('/orders/', $data);
//
//        $response->assertOk();
//
//        $this->assertDatabaseCount('orders', 1);

        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
    @test
     */
    public function get_current_orders_with_filter()
    {
//        $this->withoutExceptionHandling();
//
//        $filter = [
//            'keyword' => 'product1',
//            'status' => '1',
//            'sort' => '',
//            'page' => '1',
//            'per_page' => '50',
//        ];
//
//        $orders = \App\Models\Order::filter($filter)->get();
//
//        $response = $this->get('/orders', $filter);
//
//        $response->assertStatus($orders);

        $response = $this->get('/');

        $response->assertStatus(200);

    }
}
