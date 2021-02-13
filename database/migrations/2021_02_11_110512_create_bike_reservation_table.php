<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBikeReservationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bike_reservation', function (Blueprint $table) {
            $table->id();
            $table->dateTime('reserved_from');
            $table->dateTime('reserved_to');
            $table->timestamps();

            $table->foreignId('bike_id')->constrained('bikes');
            $table->foreignId('employee_id')->constrained('employees');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bike_reservation');
    }
}
