<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParkingRegistry extends Model
{
    protected $table = 'parkerdetails_table';
    protected $primaryKey = 'Entry_id';
    public $timestamps = false;

    /* ---------- Mass-assignment ---------- */
    protected $fillable = [
        'Full_Name',
        'Id_Number',
        'Contact_Number',
        'Position',
        'Plate_Number',
        'Vehicle_Type',
        'Department',
        'Parking_counts',
    ];

    /* ---------- Virtual attributes for UI ---------- */
    protected $appends = [
        'status_label',
        'status_class',
        'is_available',
    ];

    /* ---------- Casts ---------- */
    protected $casts = [
        'Entry_id'       => 'integer',
        'Id_Number'      => 'string',
        'Contact_Number' => 'string',
        'Parking_counts' => 'string',
    ];

    /* ---------- Static options (dropdowns) ---------- */
    public const VEHICLE_TYPES  = ['Car', 'Motorcycle'];
    public const PARKING_LABELS = ['No Parking Limit', 'Temporary Parking'];
    public const DEPARTMENTS    = ['—', 'CCS', 'CBA', 'CASED', 'ENGINEERING', 'CSS'];

    /* ---------- Validation ---------- */
    public static function rules(bool $update = false): array
    {
        return [
            'Full_Name'       => ['required','string','min:1','max:255'],
            'Id_Number'       => ['required','string','max:50'],
            'Contact_Number'  => ['nullable','string','max:50'],
            'Position'        => ['nullable','string','max:100'],
            'Plate_Number'    => ['nullable','string','max:50'],
            'Vehicle_Type'    => ['required','in:Car,Motorcycle'],
            'Department'      => ['nullable','string','max:100'],
            'Parking_counts'  => ['nullable','string','max:50'],
        ];
    }

    public static function messages(): array
    {
        return [
            'Full_Name.required'    => 'Please enter the full name.',
            'Full_Name.min'         => 'Full name must have at least 1 character.',
            'Id_Number.required'    => 'Please enter the ID number.',
            'Vehicle_Type.required' => 'Please select a vehicle type.',
            'Vehicle_Type.in'       => 'Vehicle type must be Car or Motorcycle.',
        ];
    }

    /* ---------- Helpers for controller ---------- */
    public function applyValidated(array $data): self
    {
        $this->fill($data);
        return $this;
    }

    public function toFormData(): array
    {
        return [
            'Full_Name'       => $this->Full_Name,
            'Id_Number'       => $this->Id_Number,
            'Contact_Number'  => $this->Contact_Number,
            'Position'        => $this->Position,
            'Plate_Number'    => $this->Plate_Number,
            'Vehicle_Type'    => $this->Vehicle_Type,
            'Department'      => $this->Department,
            'Parking_counts'  => (string) $this->Parking_counts,
        ];
    }

    /* ---------- Computed attributes for badge ---------- */
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
        if ($val === 'no parking limit') return true;
        if ($val === 'temporary parking') return false;

        return false;
    }

    public function getStatusLabelAttribute(): string
    {
        $raw = $this->Parking_counts;

        if (is_numeric($raw)) {
            return (int)$raw > 0 ? "{$raw}" : 'Not Available';
        }

        $val = strtolower(trim((string)$raw));
        return match ($val) {
            'no parking limit'  => 'No Parking Limit',
            'temporary parking' => 'Temporary Parking',
            'null', ''          => 'Not Available',
            default             => ucfirst($val),
        };
    }

    public function getStatusClassAttribute(): string
    {
        $raw = $this->Parking_counts;

        if (is_numeric($raw)) {
            return (int)$raw > 0 ? 'count' : 'temp';
        }

        $val = strtolower(trim((string)$raw));
        return match ($val) {
            'no parking limit'  => 'unlimited',
            'temporary parking' => 'temp',
            'null', ''          => 'temp',
            default             => 'count',
        };
    }

    /* ---------- Query scopes (use in repos/controllers if desired) ---------- */
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
                $w->where('Full_Name', 'like', "%{$term}%")
                  ->orWhere('Plate_Number', 'like', "%{$term}%")
                  ->orWhere('Department', 'like', "%{$term}%")
                  ->orWhere('Id_Number', 'like', "%{$term}%")
                  ->orWhere('Position', 'like', "%{$term}%");
            });
        }
        return $q;
    }

    public function scopeSorted($q, string $field = 'Full_Name', string $dir = 'asc')
    {
        $whitelist = [
            'Full_Name', 'Vehicle_Type', 'Department',
            'Id_Number', 'Plate_Number', 'Position', 'Parking_counts'
        ];
        if (!in_array($field, $whitelist, true)) {
            $field = 'Full_Name';
        }
        $dir = strtolower($dir) === 'desc' ? 'desc' : 'asc';

        return $q->orderBy($field, $dir)
                 ->orderBy('Full_Name'); 
    }
}
