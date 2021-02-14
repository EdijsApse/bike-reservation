<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;

use Tests\TestCase;

class BikeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * test for creating and retrieving bikes.
     *
     * @return void
     */
    public function testBikes()
    {
        $this->getJson('/api/bikes')->assertJsonCount(0, 'bikes');

        $this->postJson('/api/bikes')->assertJsonFragment(['success' => false]);

        $this->postJson('/api/bikes', ['name' => 'Some Bike'])
            ->assertJsonFragment(['success' => true])
            ->assertJsonPath('bike.name', 'Some Bike');

        $this->getJson('/api/bikes')->assertJsonCount(1, 'bikes');
    }
}
