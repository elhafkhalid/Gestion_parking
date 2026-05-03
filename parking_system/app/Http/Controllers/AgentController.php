<?php
namespace App\Http\Controllers;

use App\Models\Parking;
use App\Models\ParkingRecord;
use App\Models\Place;
use App\Models\Reservation;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    public function index(Request $request)
    {
        $section = $request->section ?? 'dashboard';

        $agent = auth()->user();

        $parking = $agent->parking;

        $parkingId = optional($parking)->id;

        $totalPlaces = Place::where('parking_id', $parkingId)->count();

        $freePlaces = Place::where('parking_id', $parkingId)
            ->where('is_occupied', false)
            ->count();

        $occupiedPlaces = Place::where('parking_id', $parkingId)
            ->where('is_occupied', true)
            ->count();

        $currentVehicles = ParkingRecord::whereHas('place', function ($q) use ($parkingId) {
            $q->where('parking_id', $parkingId);
        })
            ->whereNull('exit_time')
            ->count();

        
        $lastEntry = ParkingRecord::whereHas('place', function ($q) use ($parkingId) {
            $q->where('parking_id', $parkingId);
        })
            ->latest('entry_time')
            ->first();

            
        $lastEntryTime = $lastEntry ? $lastEntry->entry_time : null;

        $lastExit = ParkingRecord::whereHas('place', function ($q) use ($parkingId) {
            $q->where('parking_id', $parkingId);
        })
            ->whereNotNull('exit_time')
            ->latest('exit_time')
            ->first();

        $lastExitTime = $lastExit ? $lastExit->exit_time : null;

        $todayRevenue = ParkingRecord::whereHas('place', function ($q) use ($parkingId) {
            $q->where('parking_id', $parkingId);
        })
            ->whereDate('exit_time', date('Y-m-d'))
            ->sum('total_price');

        $latestVehicles = ParkingRecord::with(['vehicle', 'place'])
            ->whereHas('place', function ($q) use ($parkingId) {
                $q->where('parking_id', $parkingId);
            })
            ->whereNull('exit_time')
            ->latest('entry_time')
            ->take(5)
            ->get();

        $freePlacesList = Place::where('parking_id', $parkingId)
            ->where('is_occupied', false)
            ->get();

        $parkings = $parking
            ? collect([$parking])
            : collect();

        $vehicles = Vehicle::all();

    
        $recordsActif = ParkingRecord::with(['vehicle', 'place'])
            ->whereHas('place', function ($q) use ($parkingId) {
                $q->where('parking_id', $parkingId);
            })
            ->whereNull('exit_time')
            ->get();

        $recordsNotActif = ParkingRecord::with(['vehicle', 'place'])
            ->whereHas('place', function ($q) use ($parkingId) {
                $q->where('parking_id', $parkingId);
            })
            ->whereNotNull('exit_time')
            ->get();

       
        $reservations = Reservation::with(['user', 'vehicle', 'place.parking'])
            ->whereHas('place', function ($q) use ($parkingId) {
                $q->where('parking_id', $parkingId);
            })
            ->whereNull('canceled_at')
            ->get();

        $places = Place::where('parking_id', $parkingId)->get();

        return view('agent.dashboard', compact(
            'section',
            'parking',
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
            'recordsNotActif',
            'reservations',
        ));
    }

    public function storeEntry(Request $request) {
        $request->validate([
            'plate_number' => 'required',
            'marque'       => 'required|string|max:255',
            'place_id'     => 'required|exists:places,id',
        ]);

        $vehicle = Vehicle::firstOrCreate(
            ['plate_number' => $request->plate_number],
            ['marque' => $request->marque]
        );

        $place = Place::findOrFail($request->place_id);

        $alreadyInside = ParkingRecord::where('vehicle_id', $vehicle->id)
            ->whereNull('exit_time')
            ->exists();

        if ($alreadyInside) {
            return back()->with('error', 'vehicule deja parke');
        }

        $vehicleReservation = Reservation::where('vehicle_id', $vehicle->id)
            ->whereNull('canceled_at')
            ->first();

        if ($vehicleReservation) {
            return back()->with('error', 'ce vehicule a reserve');
        }

        if ($place->is_occupied) {
            return back()->with('error', 'place occupe');
        }

        ParkingRecord::create([
            'vehicle_id' => $vehicle->id,
            'place_id'   => $place->id,
            'agent_id'   => auth()->id(),
            'entry_time' => now(),
        ]);

        $place->update([
            'is_occupied' => true,
        ]);

        return back()->with('success', 'entre enregiste');
    }


    public function storeExit($id)
    {

        $record = ParkingRecord::with(['vehicle', 'place'])->findOrFail($id);

        if ($record->exit_time) {
            return back()->with('error', 'Vehicule deja sorti');
        }

        $entryTime = $record->entry_time;
        $exitTime  = now();

        $minutes     = $entryTime->diffInMinutes($exitTime);
        $parking     = $record->place->parking;
        $pricePerMin = $parking->price / 60;

        $totalPrice = $minutes * $pricePerMin;

        $record->update([
            'exit_time'   => $exitTime,
            'total_price' => $totalPrice,
        ]);

        $record->place->update([
            'is_occupied' => false,
        ]);

        return back()->with(
            'success',
            "Sortie effectue"
        );
    }

    public function cancelReservation($id)
    {
        $reservation = Reservation::findOrFail($id);

        $reservation->place->update([
            'is_occupied' => false,
        ]);

        $reservation->update([
            'canceled_at' => now(),
        ]);

        return back()->with('success', 'reservation annule');
    }

    public function confirmReservation($id)
    {
        $reservation = Reservation::findOrFail($id);

        ParkingRecord::create([
            'vehicle_id' => $reservation->vehicle_id,
            'place_id'   => $reservation->place_id,
            'agent_id'   => auth()->id(),
            'entry_time' => now(),
        ]);

        $reservation->place->update([
            'is_occupied' => true,
        ]);

    
        return back()->with('success', 'entre confirme');
    }

    public function getPlaces($parkingId)
    {
        $places = Place::where('parking_id', $parkingId)
            ->where('is_occupied', false)
            ->get();

        return response()->json($places);
    }
}
