<?php

namespace App\Http\Controllers;

use App\Models\ParkingRecord;
use Illuminate\Http\Request;

class ParkingRecordController extends Controller
{
    // LIST
    public function index()
    {
        // Keep your current ordering; the view renders parker_info from the model.
        $records = ParkingRecord::orderBy('Record_ID')->get();
        return view('ParkingRecords', compact('records'));
    }

    // CREATE (Add)
    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'Date_occupation'               => ['required', 'date'],
                'ParkerDetails_Table_Entry_id'  => ['required', 'integer', 'exists:parkerdetails_table,Entry_id'],
            ],
            [
                'ParkerDetails_Table_Entry_id.exists' => 'Invalid Parker ID — that ID does not exist in Parker Details.',
            ]
        );

        ParkingRecord::create($validated);

        return redirect()
            ->route('records.index')
            ->with('ok', 'Record successfully added.');
    }

    // UPDATE (Edit date only)
    public function update(Request $request, $record)
    {
        // ✅ Only validate the date for updates
        $validated = $request->validate([
            'Date_occupation' => ['required', 'date'],
        ]);

        $rec = ParkingRecord::findOrFail($record);
        $rec->update([
            'Date_occupation' => $validated['Date_occupation'],
        ]);

        return redirect()
            ->route('records.index')
            ->with('ok', 'Date updated.');
    }

    // DELETE
    public function destroy($record)
    {
        $rec = ParkingRecord::findOrFail($record);
        $rec->delete();

        return redirect()
            ->route('records.index')
            ->with('ok', 'Record deleted.');
    }
}
