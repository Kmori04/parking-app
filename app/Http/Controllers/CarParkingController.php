<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CarParkingStatus;
use App\Models\MotorcycleParkingStatus; 
use Illuminate\Support\Facades\DB; 

class CarParkingController extends Controller
{
       public function getAllCarParkingStatus() {
        $CarParkingStatusData = CarParkingStatus::all();


        return $CarParkingStatusData;

    }

      public function showAllCarParkingStatus(){
        $CarParkingStatusData = $this ->getAllCarParkingStatus();

        return view('home', compact('CarParkingStatusData'));
      }

      public function availability(){

        return view('AvailabilityMenu');
      }
      public function cars(){

        $CarParkingStatusData = CarParkingStatus::all();
        return view('availability_cars', compact('CarParkingStatusData'));
    }
        public function updateStatus(Request $request, $slotId)
        {
        $validated = $request->validate([
            'status' => ['required', 'integer', 'in:1,2'],
        ]);

        $slot = CarParkingStatus::findOrFail($slotId);
        $slot->Availability_Table_Available_id = (int) $validated['status'];
        $slot->save();

        return back()->with('success', 'Status updated successfully.');
    }

   public function motors() {
    // use your Eloquent model (no need for DB::table)
    $MotorcycleParkingStatusData = \App\Models\MotorcycleParkingStatus::all();

    // pass the variable name used by your Blade file
    return view('availability_motors', compact('MotorcycleParkingStatusData'));
}

public function updateMotorStatus(Request $request, $slotId) {
    $validated = $request->validate([
        'status' => ['required', 'integer', 'in:1,2'],
    ]);

    // update via Eloquent
    $slot = \App\Models\MotorcycleParkingStatus::findOrFail($slotId);
    $slot->Availability_Table_Available_id = (int) $validated['status'];
    $slot->save();

    return back()->with('success', 'Motor slot status updated successfully.');
}

}
