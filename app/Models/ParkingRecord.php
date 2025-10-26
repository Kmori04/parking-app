<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParkingRecord extends Model
{
   
    protected $table = 'parkingrecords_table';

    protected $primaryKey = 'Record_ID';
    public $timestamps = false;

    protected $fillable = [
        'Date_occupation',
        'ParkerDetails_Table_Entry_id',
    ];
}
