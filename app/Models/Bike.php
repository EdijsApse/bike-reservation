<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BikeReservation;

class Bike extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    /**
     * Check if reservation already exists for current bike
     * 
     * @param $from DateTime when reservation needs to start
     * @param $to DateTime when reservation needs to start
     * 
     * @return bool
     */
    public function isReserved($from, $to)
    {
        if (BikeReservation::where('bike_id', $this->id)
        ->where('reserved_from', '<', $from)
        ->where('reserved_to', '>', $from)
        ->count()) return true;

        if (BikeReservation::where('bike_id', $this->id)
        ->where('reserved_from', '>', $to)
        ->where('reserved_to', '<', $to)
        ->count()) return true;

        return false;
    }
}
