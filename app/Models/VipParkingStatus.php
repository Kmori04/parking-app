<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VipParkingStatus extends Model

{
    protected $table = 'vipparkingstatus_table';
    public $timestamps = false;

    protected $primaryKey = 'Slot_id';

    protected $fillable = [
        'Slot_number',
        'Availability_Table_Available_id'
    ];
}
