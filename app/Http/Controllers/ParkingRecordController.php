<?php

namespace App\Http\Controllers;
use App\Models\ParkingRecord;

class ParkingRecordController extends Controller
{
    public function index()
    {
      
        $records = ParkingRecord::orderBy('Record_ID')->get();

        return view('ParkingRecords', compact('records'));
    }

    // ----- [ADD] begin: CRUD for ParkingRecord -----

    public function store(\Illuminate\Http\Request $request)
    {
        $data = $request->validate([
            'Date_occupation'              => ['required', 'date'],
            'ParkerDetails_Table_Entry_id' => ['required', 'integer'],
        ]);

        \App\Models\ParkingRecord::create($data);

        return redirect()->route('records.index')->with('ok', 'Record added.');
    }

    public function edit($record)
    {
        $rec = \App\Models\ParkingRecord::findOrFail($record);
        $parkers = \App\Models\ParkingRegistry::orderBy('Full_Name')
            ->get(['Entry_id', 'Full_Name', 'Plate_Number', 'Position', 'Contact_Number']);

        return view('ParkingRecords_edit', [
            'record'  => $rec,
            'parkers' => $parkers,
        ]);
    }

    public function update(\Illuminate\Http\Request $request, $record)
    {
        $rec = \App\Models\ParkingRecord::findOrFail($record);

        $data = $request->validate([
            'Date_occupation'              => ['required', 'date'],
            'ParkerDetails_Table_Entry_id' => ['required', 'integer'],
        ]);

        $rec->fill($data)->save();

        return redirect()->route('records.index')->with('ok', 'Record updated.');
    }

    public function destroy($record)
    {
        $rec = \App\Models\ParkingRecord::findOrFail($record);
        $rec->delete();

        return redirect()->route('records.index')->with('ok', 'Record deleted.');
    }

    // ----- [ADD] end -----
}
