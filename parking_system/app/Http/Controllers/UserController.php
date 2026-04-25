<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Parking;
use App\Models\Place;
use App\Models\Reservation;
use App\Models\Vehicle;


class UserController extends Controller
{
    public function index(Request $request)
    {
        $section = $request->section ?? 'home';

        $parkings = Parking::all();
        $selectedParking = null;
        $places = collect();
        $availablePlaces = 0;

        if ($request->parking) {
            $selectedParking = Parking::find($request->parking);

            if ($selectedParking) {
                $places = $selectedParking->places()->where('is_occupied', false)->get();
                $availablePlaces = $places->count();
            }
        }

        $reservations = Reservation::with(['place.parking', 'vehicle'])
            ->where('user_id', auth()->id())
            ->whereNull('canceled_at')
            ->latest()
            ->get();
        
        $history = Reservation::all();

        return view('client.dashboard', compact(
            'section',
            'parkings',
            'selectedParking',
            'places',
            'availablePlaces',
            'reservations',
            'history',
        ));
    }

    public function reserve(Request $request) {

        $request->validate([
            'place_id' => 'required|exists:places,id',
            'plate_number' => 'required',
            'marque' => 'required',
            'reservation_date' => 'required|date',
            'reservation_time' => 'required',
        ]);

        $place = Place::findOrFail($request->place_id);

        $alreadyReserved = Reservation::where('user_id', auth()->id())
            ->whereNull('canceled_at')
            ->exists();

        if ($alreadyReserved) {
            return back()->with('error', 'Vous avez deja une reservation ');
        }

        $vehicle = Vehicle::firstOrCreate(
            ['plate_number' => $request->plate_number],
            ['marque' => $request->marque]
        );

        $reservation = Reservation::create([
            'user_id' => auth()->id(),
            'place_id' => $place->id,
            'vehicle_id' => $vehicle->id,
            'reservation_date' => $request->reservation_date,
            'reservation_time' => $request->reservation_time,
            'reserved_at' => now(),
        ]);

        return redirect()->route('client.dashboard')
            ->with('success', 'reservation reussie');
    }

    public function cancel($id)
    {
        $reservation = Reservation::where('user_id', auth()->id())
            ->findOrFail($id);

        $reservation->update([
            'canceled_at' => now()
        ]);

        return redirect()->route('client.dashboard')
            ->with('success', 'reservation anulee');
    }

    public function showReservation($id)
    {
        $reservation = Reservation::with(['place.parking', 'vehicle'])
            ->findOrFail($id);

        return view('client.reservation', compact('reservation'));
    }
}
