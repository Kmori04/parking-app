<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CarParkingStatus;
use App\Models\MotorcycleParkingStatus; 
use App\Models\VipParkingStatus;
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
   
    $MotorcycleParkingStatusData = \App\Models\MotorcycleParkingStatus::all();

    
    return view('availability_motors', compact('MotorcycleParkingStatusData'));
}

            public function updateMotorStatus(Request $request, $slotId) {
                 $validated = $request->validate([
                'status' => ['required', 'integer', 'in:1,2'],
          ]);

    
            $slot = \App\Models\MotorcycleParkingStatus::findOrFail($slotId);
           $slot->Availability_Table_Available_id = (int) $validated['status'];
           $slot->save();

         return back()->with('success', 'Motor slot status updated successfully.');
      }     

public function vips()
{
    $VipParkingStatusData = VipParkingStatus::all()
        ->sortBy(function ($r) {
            // natural sort: V1, V2, V10…
            return [ substr($r->Slot_number, 0, 1), (int) substr($r->Slot_number, 1) ];
        })
        ->values();

    return view('availability_vip', compact('VipParkingStatusData'));
}
public function updateVipStatus(Request $request, $slotId)
{
    $validated = $request->validate([
        'status' => ['required', 'integer', 'in:1,2'],
    ]);

    $slot = \App\Models\VipParkingStatus::find($slotId);

    if (!$slot) {
        return response()->json(['error' => 'Slot not found'], 404);
    }

    $slot->Availability_Table_Available_id = (int) $validated['status'];
    $slot->save();

    return response()->json([
        'message' => 'VIP slot updated successfully.',
        'slot_id' => $slot->Slot_id,
        'status'  => (int) $validated['status'],
    ]);
}

}
