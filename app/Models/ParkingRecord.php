<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ParkingRegistry;

class ParkingRecord extends Model
{
    protected $table = 'parkingrecords_table';
    protected $primaryKey = 'Record_ID';
    public $timestamps = false;

    protected $fillable = [
        'Date_occupation',
        'ParkerDetails_Table_Entry_id',
    ];

    /**
     * Relationship: one record belongs to a parker
     */
    public function parker()
    {
        return $this->belongsTo(ParkingRegistry::class, 'ParkerDetails_Table_Entry_id', 'Entry_id');
    }

    /**
     * Auto-append parker display fields.
     */
    protected $appends = [
        'parker_full_name',
        'parker_plate',
        'parker_position',
        'parker_contact',
        'parker_info',
    ];

    public function getParkerFullNameAttribute()
    {
        return optional($this->parker)->Full_Name ?? '—';
    }

    public function getParkerPlateAttribute()
    {
        return optional($this->parker)->Plate_Number ?? '—';
    }

    public function getParkerPositionAttribute()
    {
        return optional($this->parker)->Position ?? '—';
    }

    public function getParkerContactAttribute()
    {
        return optional($this->parker)->Contact_Number ?? '—';
    }

    public function getParkerInfoAttribute()
    {
        $p = $this->parker;
        if (!$p) {
            return '—';
        }
        return "{$p->Full_Name} • {$p->Plate_Number} • {$p->Position} • {$p->Contact_Number}";
    }
}
