<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bike;
use Illuminate\Support\Facades\Validator;

class BikeController extends Controller
{
    public function index() {
        return [
            'bikes' => Bike::orderBy('id', 'desc')->get()
        ];
    }

    public function create(Request $request) {
        $validator = Validator::make($request->only(['name']),
            [
                'name' => 'required'
            ],
            [
                'name.required' => 'Please enter bikes name'
            ]
        );

        if ($validator->fails()) {
            return [
                'success' => false,
                'message' => $validator->errors()->first()
            ];
        }

        $bike = Bike::create($request->only(['name']));

        return [
            'success' => true,
            'bike' => $bike,
            'message' => 'Bike added to the list'
        ];
    }
}
