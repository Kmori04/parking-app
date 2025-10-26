<?php

namespace App\Http\Controllers;

use App\Models\ParkingRegistry;
use Illuminate\Http\Request;

class ParkingRegistryController extends Controller
{
    public function userData(Request $request)
    {
        $type   = $request->query('vehicle');
        $search = $request->query('q');
        $view   = $request->query('view', 'split');

        $sort   = $request->query('sort', 'Entry_id');
        $dir    = strtolower($request->query('dir', 'asc')) === 'desc' ? 'desc' : 'asc';

        // Use the REAL column names (no spaces)
        $sortMap = [
            'Entry_id'       => 'Entry_id',
            'Full_Name'      => 'Full_Name',
            'Vehicle_Type'   => 'Vehicle_Type',
            'Department'     => 'Department',
            'Id_Number'      => 'Id_Number',
            'Plate_Number'   => 'Plate_Number',
            'Position'       => 'Position',
            'Parking_counts' => 'Parking_counts',
        ];

        if (!array_key_exists($sort, $sortMap)) {
            $sort = 'Entry_id';
        }

        $q = ParkingRegistry::query();

        if ($type) {
            $q->where('Vehicle_Type', $type);
        }

        if ($search) {
            $q->where(function ($w) use ($search) {
                $w->where('Full_Name', 'like', "%{$search}%")
                  ->orWhere('Plate_Number', 'like', "%{$search}%")
                  ->orWhere('Department', 'like', "%{$search}%")
                  ->orWhere('Id_Number', 'like', "%{$search}%")
                  ->orWhere('Position', 'like', "%{$search}%");
            });
        }

        // Primary sort
        $q->orderBy($sortMap[$sort], $dir);
        // Stable tie-breaker
        $q->orderBy('Entry_id', 'asc');

        $all = $q->get();

        // Simple aggregates (only if you actually have an "availability" attribute)
        $total        = $all->count();
        $unlimitedCnt = $all->filter(fn($r) => ($r->availability ?? null) === 'unlimited')->count();
        $availableCnt = $all->filter(fn($r) => in_array(($r->availability ?? null), ['available','unlimited']))->count();
        $notAvailCnt  = $total - $availableCnt;

        if ($view === 'flat') {
            $perPage = (int) $request->query('per_page', 25);
            $page    = max(1, (int) $request->query('page', 1));
            $slice   = $all->forPage($page, $perPage)->values();

            return view('userData', [
                'userData'     => $slice,
                'total'        => $total,
                'availableCnt' => $availableCnt,
                'notAvailCnt'  => $notAvailCnt,
                'unlimitedCnt' => $unlimitedCnt,
                'vehicle'      => $type,
                'search'       => $search,
                'sort'         => $sort,
                'dir'          => $dir,
                'page'         => $page,
                'perPage'      => $perPage,
                'lastPage'     => (int) ceil(max(1, $total) / max(1, $perPage)),
                'query'        => $request->query(),
            ]);
        }

        return view('userData', [
            'userData'     => $all,
            'total'        => $total,
            'availableCnt' => $availableCnt,
            'notAvailCnt'  => $notAvailCnt,
            'unlimitedCnt' => $unlimitedCnt,
            'vehicle'      => $type,
            'search'       => $search,
            'sort'         => $sort,
            'dir'          => $dir,
            'page'         => 1,
            'perPage'      => max(1, $all->count()),
            'lastPage'     => 1,
            'query'        => $request->query(),
        ]);
    }

    public function edit($entry)
    {
        $user = ParkingRegistry::findOrFail($entry);

        return view('userData_edit', [
            'user' => $user,
            'options' => [
                'vehicles'    => ParkingRegistry::VEHICLE_TYPES ?? ['Car','Motorcycle'],
                'departments' => ParkingRegistry::DEPARTMENTS   ?? [],
                'labels'      => ParkingRegistry::PARKING_LABELS ?? [],
            ],
        ]);
    }

    public function update(Request $req, $entry)
    {
        $user = ParkingRegistry::findOrFail($entry);

        // Rules/messages must use the SAME keys as your form (Full_Name, etc.)
        $data = $req->validate(
            ParkingRegistry::rules(update: true),
            ParkingRegistry::messages()
        );

        // Fill + save (applyValidated just wraps fill(); keep if you have it)
        method_exists($user, 'applyValidated')
            ? $user->applyValidated($data)->save()
            : $user->fill($data)->save();

        return redirect()->route('users.index')->with('ok', 'User updated.');
    } 
}
