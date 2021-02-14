<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Bike;
use App\Models\Employee;

class BikeReservationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test for getting and creating BikeReservation records
     *
     * @return void
     */
    public function testEmployees()
    {
        $this->getJson('/api/bike-reservation')
        ->assertJsonCount(0, 'employees')
        ->assertJsonCount(0, 'bikes')
        ->assertJsonCount(0, 'bikeReservation');

        $bike = Bike::factory()->create();
        $employee = Employee::factory()->create();

        $this->getJson('/api/bike-reservation')
        ->assertJsonCount(1, 'employees')
        ->assertJsonCount(1, 'bikes')
        ->assertJsonCount(0, 'bikeReservation');

        $this->postJson('/api/bike-reservation', [])->assertJsonFragment(['success' => false]);

        $this->postJson('/api/bike-reservation', [
            'bike_id' => $bike->id,
            'employee_id' => $employee->id,
            'reserved_from' => date("Y-m-d H:i", strtotime('+1 hours')),
            'reserved_to' => date("Y-m-d H:i", strtotime('+6 hours'))
        ])
        ->assertJsonFragment(['success' => true])
        ->assertJsonPath('reservation.employee', $employee->getFullname())
        ->assertJsonPath('reservation.bike', $bike->name);
        
        $b2 = Bike::factory()->create();
        $e2 = Employee::factory()->create();

        /**
         * New Employee try to reserve bike which is already asigned in specific time period
         */
        $this->postJson('/api/bike-reservation', [
            'bike_id' => $bike->id,
            'employee_id' => $e2->id,
            'reserved_from' => date("Y-m-d H:i", strtotime('+2 hours')),
            'reserved_to' => date("Y-m-d H:i", strtotime('+8 hours'))
        ])
        ->assertJsonFragment(['success' => false]);

        /**
         * Employee who is using bike in specific time period tries to assigne new bike
         */
        $this->postJson('/api/bike-reservation', [
            'bike_id' => $b2->id,
            'employee_id' => $employee->id,
            'reserved_from' => date("Y-m-d H:i", strtotime('+3 hours')),
            'reserved_to' => date("Y-m-d H:i", strtotime('+5 hours'))
        ])
        ->assertJsonFragment(['success' => false]);


        /**
         * Assigne employee bike after reservation expires
         */

        $this->postJson('/api/bike-reservation', [
            'bike_id' => $bike->id,
            'employee_id' => $e2->id,
            'reserved_from' => date("Y-m-d H:i", strtotime('+9 hours')),
            'reserved_to' => date("Y-m-d H:i", strtotime('+10 hours'))
        ])
        ->assertJsonFragment(['success' => true])
        ->assertJsonPath('reservation.employee', $e2->getFullname())
        ->assertJsonPath('reservation.bike', $bike->name);

        $this->postJson('/api/bike-reservation', [
            'bike_id' => $b2->id,
            'employee_id' => $employee->id,
            'reserved_from' => date("Y-m-d H:i", strtotime('+7 hours')),
            'reserved_to' => date("Y-m-d H:i", strtotime('+8 hours'))
        ])
        ->assertJsonFragment(['success' => true])
        ->assertJsonPath('reservation.employee', $employee->getFullname())
        ->assertJsonPath('reservation.bike', $b2->name);

        $this->getJson('/api/bike-reservation')
        ->assertJsonCount(2, 'employees')
        ->assertJsonCount(2, 'bikes')
        ->assertJsonCount(3, 'bikeReservation');
        

        /**
         * From date less then now
         */
        $this->postJson('/api/bike-reservation', [
            'bike_id' => $bike->id,
            'employee_id' => $employee->id,
            'reserved_from' => date("Y-m-d H:i", strtotime('-1 hours')),
            'reserved_to' => date("Y-m-d H:i", strtotime('+1 hours'))
        ])
        ->assertJsonFragment(['success' => false]);

        /**
         * To date less then From date
         */
        $this->postJson('/api/bike-reservation', [
            'bike_id' => $bike->id,
            'employee_id' => $employee->id,
            'reserved_from' => date("Y-m-d H:i", strtotime('+1 hours')),
            'reserved_to' => date("Y-m-d H:i", strtotime('-2 hours'))
        ])
        ->assertJsonFragment(['success' => false]);

        /**
         * Non existing bike
         */

        $this->postJson('/api/bike-reservation', [
            'bike_id' => $b2->id + 1,
            'employee_id' => $employee->id,
            'reserved_from' => date("Y-m-d H:i", strtotime('+1 hours')),
            'reserved_to' => date("Y-m-d H:i", strtotime('-2 hours'))
        ])
        ->assertJsonFragment(['success' => false]);


        /**
         * Non existing employee
         */
        $this->postJson('/api/bike-reservation', [
            'bike_id' => $b2->id,
            'employee_id' => $e2->id + 1,
            'reserved_from' => date("Y-m-d H:i", strtotime('+1 hours')),
            'reserved_to' => date("Y-m-d H:i", strtotime('-2 hours'))
        ])
        ->assertJsonFragment(['success' => false]);
    }
}
