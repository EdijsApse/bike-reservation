<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Employee;
use App\Models\Bike;
use App\Models\BikeReservation;

use Illuminate\Foundation\Testing\RefreshDatabase;

class BikeReservationTest extends TestCase
{
    use RefreshDatabase;


    /**
     * Testing if bike can be reserved in time period when there already is reservation
     */
    public function testBikeIsReserved()
    {

        $bike = Bike::factory()->create();

        $employee = Employee::factory()->create();

        $reservation = new BikeReservation();
        $reservation->employee_id = $employee->id;
        $reservation->bike_id = $bike->id;
        $reservation->reserved_from = date("Y-m-d H:i", strtotime('+1 hours'));
        $reservation->reserved_to = date("Y-m-d H:i", strtotime('+5 hours'));

        $this->assertTrue($reservation->save());

        $this->assertTrue(
            $bike->isReserved(
                date("Y-m-d H:i", strtotime('+2 hours')),
                date("Y-m-d H:i", strtotime('+3 hours'))
            )
        );

        $this->assertTrue(
            $bike->isReserved(
                date("Y-m-d H:i", strtotime('+2 hours')),
                date("Y-m-d H:i", strtotime('+6 hours'))
            )
        );

        $this->assertFalse(
            $bike->isReserved(
                date("Y-m-d H:i", strtotime('+6 hours')),
                date("Y-m-d H:i", strtotime('+7 hours'))
            )
        );
    }

    /**
     * Testing if employee can reserve bike, when employee already has reserved bike in time period 
    */
    public function testEmployeeIsReserving()
    {
        $bike = Bike::factory()->create();

        $employee = Employee::factory()->create();

        $reservation = new BikeReservation();
        $reservation->employee_id = $employee->id;
        $reservation->bike_id = $bike->id;
        $reservation->reserved_from = date("Y-m-d H:i", strtotime('+1 hours'));
        $reservation->reserved_to = date("Y-m-d H:i", strtotime('+5 hours'));

        $this->assertTrue($reservation->save());

        $this->assertTrue(
            $employee->isReserving(
                date("Y-m-d H:i", strtotime('+2 hours')),
                date("Y-m-d H:i", strtotime('+3 hours'))
            )
        );

        $this->assertTrue(
            $employee->isReserving(
                date("Y-m-d H:i", strtotime('+2 hours')),
                date("Y-m-d H:i", strtotime('+6 hours'))
            )
        );

        $this->assertFalse(
            $employee->isReserving(
                date("Y-m-d H:i", strtotime('+6 hours')),
                date("Y-m-d H:i", strtotime('+7 hours'))
            )
        );
    }

}
