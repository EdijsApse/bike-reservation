<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bike;
use App\Models\BikeReservation;
use App\Models\Employee;
use App\Http\Resources\BikeReservationResourceCollection;
use App\Http\Resources\BikeReservationResource;
use Illuminate\Support\Facades\Validator;


class BikeReservationController extends Controller
{
    public function index()
    {
        return [
            'bikes' => Bike::orderBy('id', 'desc')->get(),
            'employees' => Employee::orderBy('id', 'desc')->get(),
            'bikeReservation' => new BikeReservationResourceCollection(
                BikeReservation::orderBy('id', 'desc')->get()
            ),
        ];
    }

    public function create(Request $request)
    {

        $validator = Validator::make($request->only(['bike_id', 'employee_id', 'reserved_from', 'reserved_to']),
            [
                'bike_id' => 'required|exists:bikes,id',
                'employee_id' => 'required|exists:employees,id',
                'reserved_from' => 'required|date',
                'reserved_to' => 'required|date'
            ],
            [
                'bike_id.required' => 'Select bike which assigne to employee',
                'bike_id.exists' => 'Bike not found',
                'employee_id.required' => 'Select employee which assigne to bike',
                'employee_id.exists' => 'Employee not found',
                'reserved_from.required' => 'From date is requried',
                'reserved_from.date' => 'From date is not valid',
                'reserved_to.required' => 'To date is requried',
                'reserved_to.date' => 'To date is not valid'
            ]
        );

        if ($validator->fails()) {
            return [
                'success' => false,
                'message' => $validator->errors()->first()
            ];
        }

        $bike = Bike::find($request->input('bike_id'));

        if ($bike->isReserved($request->input('reserved_from'), $request->input('reserved_to'))) {
            return [
                'success' => false,
                'message' => 'Bike is reserved in this time period'
            ];
        }

        $employee = Employee::find($request->input('employee_id'));

        if ($employee->isReserving($request->input('reserved_from'), $request->input('reserved_to'))) {
            return [
                'success' => false,
                'message' => 'Looks like employee has bike assigned in this time period'
            ];
        }

        $reservation = BikeReservation::create($request->only(['bike_id', 'employee_id', 'reserved_from', 'reserved_to']));

        return [
            'success' => true,
            'reservation' => new BikeReservationResource($reservation),
            'message' => 'New reservation created'
        ];
    }
}
