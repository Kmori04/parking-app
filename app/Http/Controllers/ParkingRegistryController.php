<?php

namespace App\Http\Controllers;

use App\Models\ParkingRegistry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParkingRegistryController extends Controller
{
    public function userData(Request $request)
{
    $type   = $request->query('vehicle');
    $search = $request->query('q');
    $view   = $request->query('view', 'split');

    $sort   = $request->query('sort', 'Entry_id');
    $dir    = strtolower($request->query('dir', 'asc')) === 'desc' ? 'desc' : 'asc';

    // IMPORTANT: use your real column names (no spaces)
    $sortMap = [
        'Entry_id'       => 'Entry_id',
        'Full_name'      => 'Full_name',
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
            $w->where('Full_name', 'like', "%{$search}%")
              ->orWhere('Plate_Number', 'like', "%{$search}%")
              ->orWhere('Department', 'like', "%{$search}%")
              ->orWhere('Id_Number', 'like', "%{$search}%")
              ->orWhere('Position', 'like', "%{$search}%");
        });
    }

    $q = ($sort === 'Full_name')
        ? $q->orderBy('Full_name', $dir)
        : $q->orderBy($sortMap[$sort], $dir);

    $q = $q->orderBy('Entry_id', 'asc');

    $all = $q->get();

    $total        = $all->count();
    $unlimitedCnt = $all->filter(fn($r) => $r->availability === 'unlimited')->count();
    $availableCnt = $all->filter(fn($r) => in_array($r->availability, ['available','unlimited']))->count();
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

        
}
