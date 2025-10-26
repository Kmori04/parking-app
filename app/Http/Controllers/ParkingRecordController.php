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
}
