<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BikeReservation extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'bike_id',
        'employee_id',
        'reserved_from',
        'reserved_to'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bike_reservation';

    /**
     * Get bike from bikes table by foreign key bike_id
     */
    public function bike()
    {
        return $this->belongsTo(Bike::class);
    }

    /**
     * Get employee from employees table by foreign key employee_id
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

}
