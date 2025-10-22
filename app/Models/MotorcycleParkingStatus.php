<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MotorcycleParkingStatus extends Model

{
    protected $table = 'motorcycleparkingstatus_table';
    public $timestamps = false;

    protected $primaryKey = 'Slot_id';

    protected $fillable = [
        'Slot_number',
        'Availability_Table_Available_id'
    ];
}
