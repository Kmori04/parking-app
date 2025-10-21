<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarParkingStatus extends Model
{
    protected $table = 'carparkingstatus_table';
    public $timestamps = false;

    protected $primaryKey = 'Slot_id';

    protected $fillable = [
        'Slot_number',
        'Availability_Table_Available_id'
    ];
}
