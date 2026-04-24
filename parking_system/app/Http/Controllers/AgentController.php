<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ParkingRecord;
use App\Models\Place;
use App\Models\Parking;
use App\Models\Vehicle;

class AgentController extends Controller
{
    public function index(Request $request)
    {
        $section = $request->section ?? 'dashboard';

        $totalPlaces = Place::count();
        $freePlaces = Place::where('is_occupied', false)->count();
        $occupiedPlaces = Place::where('is_occupied', true)->count();

        $currentVehicles = ParkingRecord::whereNull('exit_time')->count();

        $lastEntry = ParkingRecord::latest('entry_time')->first();
        $lastEntryTime = $lastEntry ? $lastEntry->entry_time : null;

        $lastExit = ParkingRecord::whereNotNull('exit_time')
            ->latest('exit_time')
            ->first();
        $lastExitTime = $lastExit ? $lastExit->exit_time : null;

        $todayRevenue = ParkingRecord::whereDate('exit_time', date('Y-m-d'))
            ->sum('total_price');


        $latestVehicles = ParkingRecord::with('vehicle', 'place')
            ->whereNull('exit_time')
            ->latest('entry_time')
            ->take(5)
            ->get();

        $freePlacesList = Place::where('is_occupied', false)->get();
        $parkings = Parking::all();
        $vehicles = Vehicle::all();

        $recordsActif = ParkingRecord::with(['vehicle', 'place'])
            ->whereNull('exit_time')
            ->get();

        $recordsNotActif = ParkingRecord::with(['vehicle', 'place'])
            ->whereNotNull('exit_time')
            ->get();

        $places = place::all();
        return view('agent.dashboard', compact(
            'section',
            'totalPlaces',
            'freePlaces',
            'occupiedPlaces',
            'currentVehicles',
            'lastEntryTime',
            'lastExitTime',
            'todayRevenue',
            'latestVehicles',
            'freePlacesList',
            'parkings',
            'vehicles',
            'recordsActif',
            'places',
            'recordsNotActif'
        ));
    }


    public function storeEntry(Request $request)
    {
        $request->validate([
            'plate_number' => 'required',
            'type' => 'required|in:car,motorcycle',
            'place_id' => 'required|exists:places,id',
        ]);

        
        $vehicle = Vehicle::where('plate_number', $request->plate_number)->first();

        
        if (!$vehicle) {
            $vehicle = Vehicle::create([
                'plate_number' => $request->plate_number,
                'type' => $request->type
            ]);
        }

        
        $alreadyInside = ParkingRecord::where('vehicle_id', $vehicle->id)
            ->whereNull('exit_time')
            ->exists();

        if ($alreadyInside) {
            return back()->with('error', 'Vehicule deja dans le parking');
        }

        
        $place = Place::findOrFail($request->place_id);

        if ($place->is_occupied) {
            return back()->with('error', 'place occupe');
        }

       
        ParkingRecord::create([
            'vehicle_id' => $vehicle->id,
            'place_id' => $place->id,
            'agent_id' => auth()->id(),
            'entry_time' => now(),
        ]);

        
        $place->update([
            'is_occupied' => true
        ]);

        return back()->with('success', 'Entre enregistre');
    }

    public function storeExit($id)
    {
        
        $record = ParkingRecord::with(['vehicle', 'place.parking'])->findOrFail($id);

        
        if ($record->exit_time) {
            return back()->with('error', 'Vehicule deja sorti');
        }

        
        $entryTime = $record->entry_time;
        $exitTime = now();

        $minutes = $entryTime->diffInMinutes($exitTime);
        $hours = ceil($minutes / 60);

        
        $type = $record->vehicle->type;

        
        $parking = $record->place->parking;

      
        if ($type === 'car') {
            $pricePerHour = $parking->price_car;
        } else {
            $pricePerHour = $parking->price_motorcycle;
        }

        
        $totalPrice = $hours * $pricePerHour;

       
        $record->update([
            'exit_time' => $exitTime,
            'total_price' => $totalPrice
        ]);

        
        $record->place->update([
            'is_occupied' => false
        ]);

        return back()->with(
            'success',
            "Sortie effectue | Durée: {$hours}h | Total: {$totalPrice} DH"
        );
    }

    public function getPlaces($parkingId)
    {
        $places = Place::where('parking_id', $parkingId)
            ->where('is_occupied', false)
            ->get();

        return response()->json($places);
    }
}
