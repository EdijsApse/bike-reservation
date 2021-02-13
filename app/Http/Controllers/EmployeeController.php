<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    public function index() {
        return [
            'employees' => Employee::orderBy('id', 'desc')->get()
        ];
    }

    public function create(Request $request) {

        $validator = Validator::make($request->only(['surname', 'name']),
            [
                'name' => 'required',
                'surname' => 'required'
            ],
            [
                'name.required' => 'Please enter employees name',
                'surname.required' => 'Please enter employees surname'
            ]
        );

        if ($validator->fails()) {
            return [
                'success' => false,
                'message' => $validator->errors()->first()
            ];
        }

        $employee = Employee::create($request->only(['surname', 'name']));

        return [
            'success' => true,
            'employee' => $employee,
            'message' => 'Employee added to the list'
        ];
    }
}
