<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'surname'
    ];

    /**
     * Gets fullname by concatinating name + surname
     * 
     * @return string
     */
    public function getFullname()
    {
        return $this->name . ' ' . $this->surname;
    }


    /**
     * Check if reservation already exists for current employee
     * 
     * @param $from DateTime when reservation needs to start
     * @param $to DateTime when reservation needs to start
     * 
     * @return bool
     */
    public function isReserving($from, $to)
    {
        if (BikeReservation::where('employee_id', $this->id)
        ->where('reserved_from', '<', $from)
        ->where('reserved_to', '>', $from)
        ->count()) return true;

        if (BikeReservation::where('employee_id', $this->id)
        ->where('reserved_from', '>', $to)
        ->where('reserved_to', '<', $to)
        ->count()) return true;

        return false;
    }
}
