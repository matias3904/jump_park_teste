<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\Models\User;
use App\Models\ServiceOrder;

class ServiceOrderCreateTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_creates_a_service_order_successfully()
    {
        $user = User::Create([
            'name' => 'Test User',
        ]);

        $payload = [
            'userId' => $user->id,
            'vehiclePlate' => 'SQA9524',
            'entryDateTime' => '2025-07-04 14:00:00',
            'exitDateTime' => '2025-07-12 15:30:00',
            'priceType' => 'Hora',
            'price' => 25.50,
        ];

        $response = $this->postJson(route('service-orders.store'), $payload);

        $response->assertStatus(200)
                 ->assertJsonFragment($payload);

        $this->assertDatabaseHas('service_orders', $payload);
    }

    /** @test */
    public function it_try_create_a_invalid_service_order()
    {
        $user = User::Create([
            'name' => 'Test User',
        ]);

        $payload = [
            'userId' => $user->id,
            'vehiclePlate' => 58, // Passando como inteiro, deve ser string
            'entryDateTime' => '2025-07-04 14:00:00',
            'exitDateTime' => '2025-07-12 15:30:00',
            'priceType' => 'Hora',
            'price' => -1, // Preço negativo, deve ser maior ou igual a 0
        ];

        $response = $this->postJson(route('service-orders.store'), $payload);

        $response->assertStatus(422)
                ->assertJsonValidationErrors([
                    'vehiclePlate' => [
                        "The vehicle plate field must be a string.",
                        "The vehicle plate field must be 7 characters."
                    ],
                    'price' => [
                        'The price field must be at least 0.',
                    ],
                ]);

        $this->assertDatabaseMissing('service_orders', $payload);
    }


    /** @test */
    public function it_validates_required_fields_on_store()
    {
        $response = $this->postJson(route('service-orders.store'), []);

        $response->assertStatus(422)
                ->assertJsonValidationErrors([
                    'userId',
                    'vehiclePlate',
                    'entryDateTime',
                ]);
    }
}
