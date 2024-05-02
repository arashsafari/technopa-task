<?php

namespace Tests\Feature;

use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Order::factory()->count(10)->create();
    }

    public function test_get_unprocessable_entity_if_phone_number_not_valid()
    {
        $response = $this->json('GET', route('backoffice.orders', ['phone_number' => fake()->numberBetween(1, 999999)]));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "message",
                "errors",
                "data"
            ]);
    }

    public function test_get_unprocessable_entity_if_national_code_not_number()
    {
        $response = $this->json('GET', route('backoffice.orders', ['national_code' => fake()->text(10)]));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "message",
                "errors",
                "data"
            ]);
    }

    public function test_get_unprocessable_entity_if_national_code_bigher_than_10_character()
    {
        $response = $this->json('GET', route('backoffice.orders', ['national_code' => fake()->numerify('############')]));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "message",
                "errors",
                "data"
            ]);
    }

    public function test_get_unprocessable_entity_if_min_bigher_than_not_number()
    {
        $response = $this->json('GET', route('backoffice.orders', ['min' => fake()->text(10)]));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "message",
                "errors",
                "data"
            ]);
    }

    public function test_get_unprocessable_entity_if_min_bigher_than_not_positive()
    {
        $response = $this->json('GET', route('backoffice.orders', ['min' => -1]));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "message",
                "errors",
                "data"
            ]);
    }

    public function test_get_unprocessable_entity_if_min_bigher_than_max()
    {
        $response = $this->json('GET', route('backoffice.orders', ['min' => 100, 'max' => 10]));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "message",
                "errors",
                "data"
            ]);
    }

    public function test_get_unprocessable_entity_if_max_bigher_than_not_number()
    {
        $response = $this->json('GET', route('backoffice.orders', ['max' => fake()->text(10)]));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "message",
                "errors",
                "data"
            ]);
    }

    public function test_get_unprocessable_entity_if_max_bigher_than_not_positive()
    {
        $response = $this->json('GET', route('backoffice.orders', ['max' => -1]));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "message",
                "errors",
                "data"
            ]);
    }

    public function test_get_unprocessable_entity_if_status_not_valid()
    {
        $response = $this->json('GET', route('backoffice.orders', ['status' => fake()->text(10)]));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                "message",
                "errors",
                "data"
            ]);
    }

    public function test_get_list_of_orders_if_all_data_correct()
    {
        $response = $this->json('GET', route('backoffice.orders'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                "message",
                "errors",
                "data"
            ]);
    }
}
