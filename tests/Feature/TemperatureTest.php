<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Temperature;

class TemperatureTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_can_create_a_temperature(): void
    {
        $payload = [
            'city' => 'Teste Cidade',
            'temperature_fahrenheit' => '86.0',
        ];
        $response = $this->postJson('/api/temperature', $payload);

        $response->assertStatus(201);
        $response->assertJson([
            'temperature' => ['city' => 'Teste Cidade']
        ]);

        $this->assertDatabaseHas('temperatures', ['city' => 'Teste Cidade']);
    }
    public function test_can_list_temperatures()
    {
        $temperatures = Temperature::factory()->count(3)->create();
        $response = $this->getJson('/api/temperature');
        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }
    public function test_can_update_temperature()
    {
        $temperature = Temperature::factory()->create([
            'city' => 'Teste Update',
            'temperature_fahrenheit' => '90.0',
        ]);
        $updatedData = [
            'city' => 'Teste Update1',
            'temperature_fahrenheit' => '90.1',
        ];
        $response = $this->putJson("/api/temperature/{$temperature->city}", $updatedData);
        $response->assertStatus(200);

        $this->assertDatabaseHas('temperatures', [
            'id' => $temperature->id,
            'city' => 'Teste Update1',
        ]);

    }
    public function test_can_delete_temperature()
    {
        $temperature = Temperature::factory()->create();

        $response = $this->deleteJson("/api/temperature/{$temperature->city}");
        $response->assertStatus(204);

        $this->assertDatabaseMissing('temperatures', [
            'city' => $temperature->city
        ]);
    }
}
