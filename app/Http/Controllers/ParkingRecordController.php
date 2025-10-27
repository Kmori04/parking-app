<?php

namespace App\Http\Controllers;

use App\Models\ParkingRecord;
use Illuminate\Http\Request;

class ParkingRecordController extends Controller
{
    public function index()
    {
        $records = ParkingRecord::orderBy('Record_ID')->get();
        return view('ParkingRecords', compact('records'));
    }

    // ===== STORE (Add New Record) =====
    public function store(Request $request)
    {
        // Validate with friendly rules
        $validated = $request->validate([
            'Date_occupation' => ['required', 'date'],
            'ParkerDetails_Table_Entry_id' => [
                'required',
                'integer',
                'exists:parkerdetails_table,Entry_id'
            ],
        ], [
            // Custom messages
            'ParkerDetails_Table_Entry_id.exists' => 'Invalid Parker ID — this ID does not exist in Parker Details.',
        ]);

        // If valid, save record
        ParkingRecord::create($validated);

        // Redirect back with success message
        return redirect()
            ->route('records.index')
            ->with('ok', 'Record successfully added.');
    }

    // ===== EDIT (Show Edit Page) =====
    public function edit($record)
    {
        $rec = ParkingRecord::findOrFail($record);
        $parkers = \App\Models\ParkingRegistry::orderBy('Full_Name')
            ->get(['Entry_id', 'Full_Name', 'Plate_Number', 'Position', 'Contact_Number']);

        return view('editRecord', compact('rec', 'parkers'));
    }

    // ===== UPDATE =====
    public function update(Request $request, $record)
    {
        $data = $request->validate([
            'Date_occupation' => ['required', 'date'],
            'ParkerDetails_Table_Entry_id' => [
                'required',
                'integer',
                'exists:parkerdetails_table,Entry_id'
            ],
        ], [
            'ParkerDetails_Table_Entry_id.exists' => 'Invalid Parker ID — this ID does not exist in Parker Details.',
        ]);

        $rec = ParkingRecord::findOrFail($record);
        $rec->update($data);

        return redirect()
            ->route('records.index')
            ->with('ok', 'Record successfully updated.');
    }

    // ===== DELETE =====
    public function destroy($record)
    {
        $rec = ParkingRecord::findOrFail($record);
        $rec->delete();

        return redirect()
            ->route('records.index')
            ->with('ok', 'Record deleted successfully.');
    }
}
