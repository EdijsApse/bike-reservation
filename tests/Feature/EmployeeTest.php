<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeTest extends TestCase
{

    use RefreshDatabase;

    /**
     * Test for getting and creating Employee records
     *
     * @return void
     */
    public function testEmployees()
    {
        $this->getJson('/api/employees')->assertJsonCount(0, 'employees');

        $this->postJson('/api/employees')->assertJsonFragment(['success' => false]);

        $this->postJson('/api/employees', ['name' => 'Name'])
            ->assertJsonFragment(['success' => false]);

        $this->postJson('/api/employees', ['surname' => 'Surname'])
            ->assertJsonFragment(['success' => false]);

        $this->postJson('/api/employees', ['name' => 'Name', 'surname' => 'Surname'])
            ->assertJsonFragment(['success' => true])
            ->assertJsonPath('employee.name', 'Name');

        $this->getJson('/api/employees')->assertJsonCount(1, 'employees');
    }
}
