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

    // ADD this inside the ParkingRegistry class (anywhere inside the class braces)

// Optional: simple casts (doesn't change your logic)
protected $casts = [
    'Entry_id'       => 'integer',
    'Id_Number'      => 'string',
    'Contact_Number' => 'string',
    'Parking_counts' => 'string',  // you already handle numeric/strings in accessors
];

// ---- Static options for dropdowns (use in your edit blade) ----
public const VEHICLE_TYPES = ['Car', 'Motorcycle'];
public const PARKING_LABELS = ['No Parking Limit', 'Temporary Parking']; // common strings you already handle

// Example department list (adjust to your school)
public const DEPARTMENTS = [
    '—', 'CCS', 'CBA', 'CASED', 'ENGINEERING', 'CSS',
];

// ---- Validation rules/messages you can reuse in the controller ----
public static function rules(bool $update = false): array
{
    // $update flag in case you want different rules for create vs update later
    return [
        'Full_name'       => ['required','string','max:100'],
        'Id_Number'       => ['required','string','max:50'],
        'Contact_Number'  => ['nullable','string','max:50'],
        'Position'        => ['nullable','string','max:100'],
        'Plate_Number'    => ['nullable','string','max:50'],
        'Vehicle_Type'    => ['required','in:Car,Motorcycle'],
        'Department'      => ['nullable','string','max:100'],
        // Accept either a number, or your known labels
        'Parking_counts'  => ['nullable','string','max:50'],
    ];
}

public static function messages(): array
{
    return [
        'Full_name.required'    => 'Please enter the full name.',
        'Id_Number.required'    => 'Please enter the ID number.',
        'Vehicle_Type.required' => 'Please select a vehicle type.',
        'Vehicle_Type.in'       => 'Vehicle type must be Car or Motorcycle.',
    ];
}

// ---- Helper for prefilling edit forms ----
public function toFormData(): array
{
    return [
        'Full_name'       => $this->Full_name,
        'Id_Number'       => $this->Id_Number,
        'Contact_Number'  => $this->Contact_Number,
        'Position'        => $this->Position,
        'Plate_Number'    => $this->Plate_Number,
        'Vehicle_Type'    => $this->Vehicle_Type,
        'Department'      => $this->Department,
        'Parking_counts'  => (string)$this->Parking_counts,
    ];
}

// ---- Safe fill helper for updates ----
public function applyValidated(array $data): self
{
    // Only fills the $fillable you already defined
    $this->fill($data);
    return $this;
}

}
