<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParkingRegistry extends Model
{
    protected $table = 'parkerdetails_table';
    protected $primaryKey = 'Entry_id';
    public $timestamps = false;

    protected $fillable = [
        'Full_name',
        'Id_Number',
        'Contact_Number',
        'Position',
        'Plate_Number',
        'Vehicle_Type',
        'Department',
        'Parking_counts',
    ];

    /**
     * Make these computed fields available directly in Blade:
     *  - $row->status_label
     *  - $row->status_class
     *  - $row->is_available
     */
    protected $appends = [
        'status_label',
        'status_class',
        'is_available',
    ];

    /* -----------------------
       Normalized helpers
    ------------------------*/

    // True if user has any available allowance (or unlimited)
    public function getIsAvailableAttribute(): bool
    {
        $raw = $this->Parking_counts;

        if (is_null($raw) || $raw === '' || strtolower((string)$raw) === 'null') {
            return false;
        }

        if (is_numeric($raw)) {
            return ((int) $raw) > 0;
        }

        $val = strtolower(trim((string)$raw));
        if ($val === 'no parking limit') return true;   // treat as available
        if ($val === 'temporary parking') return false; // treat as not available

        // Any other strings fall back to not available
        return false;
    }

    // Text you show in the badge column
    public function getStatusLabelAttribute(): string
    {
        $raw = $this->Parking_counts;

        if (is_numeric($raw)) {
            return (int)$raw > 0 ? "{$raw}" : 'Not Available';
        }

        $val = strtolower(trim((string)$raw));
        return match ($val) {
            'no parking limit' => 'No Parking Limit',
            'temporary parking' => 'Temporary Parking',
            'null', '' => 'Not Available',
            default => ucfirst($val),
        };
    }

    // CSS class to style the badge (matches your userData.css)
    public function getStatusClassAttribute(): string
    {
        $raw = $this->Parking_counts;

        if (is_numeric($raw)) {
            return (int)$raw > 0 ? 'count' : 'temp';
        }

        $val = strtolower(trim((string)$raw));
        return match ($val) {
            'no parking limit' => 'unlimited',
            'temporary parking' => 'temp',
            'null', '' => 'temp',
            default => 'count', // neutral/green for any other string
        };
    }

    /* -----------------------
       Query scopes (optional)
       Use: ParkingRegistry::vehicle('Car')->search('CCS')->sorted('Full_name')->get();
    ------------------------*/

    public function scopeVehicle($q, ?string $type)
    {
        if ($type) {
            $q->where('Vehicle_Type', $type);
        }
        return $q;
    }

    public function scopeSearch($q, ?string $term)
    {
        if ($term) {
            $q->where(function ($w) use ($term) {
                $w->where('Full_name', 'like', "%{$term}%")
                  ->orWhere('Plate_Number', 'like', "%{$term}%")
                  ->orWhere('Department', 'like', "%{$term}%")
                  ->orWhere('Id_Number', 'like', "%{$term}%")
                  ->orWhere('Position', 'like', "%{$term}%");
            });
        }
        return $q;
    }

    public function scopeSorted($q, string $field = 'Full_name', string $dir = 'asc')
    {
        $whitelist = [
            'Full_name', 'Vehicle_Type', 'Department',
            'Id_Number', 'Plate_Number', 'Position', 'Parking_counts'
        ];
        if (!in_array($field, $whitelist, true)) {
            $field = 'Full_name';
        }
        $dir = strtolower($dir) === 'desc' ? 'desc' : 'asc';
        return $q->orderBy($field, $dir)->orderBy('Full_name');
    }
}
