<?php

use App\Models\ServiceOrder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->baseUri = '/api/service-orders';
});

test('it can list service orders', function () {
    $user = User::factory()->create();
    ServiceOrder::factory()->count(3)->create(['userId' => $user->id]);

    $response = getJson($this->baseUri);

    $response->assertStatus(200)
        ->assertJsonCount(3)
        ->assertJsonStructure([
            '*' => [
                'id',
                'vehiclePlate',
                'entryDateTime',
                'exitDateTime',
                'priceType',
                'price',
                'userName',
            ],
        ]);
});

test('it can create a service order with valid data', function () {
    $user = User::factory()->create();
    $serviceOrderData = [
        'vehiclePlate'   => 'ABC1D23',
        'entryDateTime'  => now()->format('Y-m-d H:i:s'),
        'exitDateTime'   => now()->addHour()->format('Y-m-d H:i:s'),
        'priceType'      => 'Hora',
        'price'          => 50.50,
        'userId'         => $user->id,
    ];

    $response = postJson($this->baseUri, $serviceOrderData);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => 'Ordem de serviço criada com sucesso',
        ]);

    $this->assertDatabaseHas('service_orders', [
        'vehiclePlate' => 'ABC1D23',
        'userId' => $user->id,
    ]);
});

test('it returns validation error when required fields are missing', function (array $payload, array $errors) {
    $response = postJson($this->baseUri, $payload);

    $response->assertStatus(422)
        ->assertJsonValidationErrors($errors);
})->with([
    'missing vehiclePlate' => [
        'payload' => fn() => ['entryDateTime' => now()->format('Y-m-d H:i:s'), 'userId' => User::factory()->create()->id],
        'errors' => ['vehiclePlate']
    ],
    'missing entryDateTime' => [
        'payload' => fn() => ['vehiclePlate' => 'ABC1D23', 'userId' => User::factory()->create()->id],
        'errors' => ['entryDateTime']
    ],
    'missing userId' => [
        'payload' => ['vehiclePlate' => 'ABC1D23', 'entryDateTime' => now()->format('Y-m-d H:i:s')],
        'errors' => ['userId']
    ],
]);

test('it returns validation error for invalid data formats', function (array $payload, array $errors) {
    $user = User::factory()->create();
    $payload['userId'] = $user->id;

    $response = postJson($this->baseUri, $payload);

    $response->assertStatus(422)
        ->assertJsonValidationErrors($errors);
})->with([
    'invalid vehiclePlate size' => [
        'payload' => ['vehiclePlate' => 'INVALID', 'entryDateTime' => now()->format('Y-m-d H:i:s')],
        'errors' => ['vehiclePlate']
    ],
    'invalid entryDateTime format' => [
        'payload' => ['vehiclePlate' => 'ABC1D23', 'entryDateTime' => 'invalid-date'],
        'errors' => ['entryDateTime']
    ],
    'exitDateTime before entryDateTime' => [
        'payload' => [
            'vehiclePlate' => 'ABC1D23',
            'entryDateTime' => now()->format('Y-m-d H:i:s'),
            'exitDateTime' => now()->subHour()->format('Y-m-d H:i:s')
        ],
        'errors' => ['exitDateTime']
    ],
    'non-existent userId' => [
        'payload' => ['vehiclePlate' => 'ABC1D23', 'entryDateTime' => now()->format('Y-m-d H:i:s'), 'userId' => 999],
        'errors' => ['userId']
    ],
]);
