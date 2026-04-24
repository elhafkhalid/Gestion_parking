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
        $user = Auth::user();
        $section = $request->section ?? 'home';
        $parkings = Parking::all();
        $selectedParking = null;
        $availablePlaces = Place::where('is_occupied', false)->count();
        $places = [];

        if ($section === 'parkings') {

            if ($request->parking) {
                $selectedParking = Parking::find($request->parking);
            } else {

                $selectedParking = $parkings->first();
            }

            if ($selectedParking) {
                $availablePlaces = $selectedParking
                    ->places()
                    ->where('is_occupied', false)
                    ->count();
            }
        }


        if ($selectedParking) {
            $places = $selectedParking->places()
                ->where('is_occupied', false)
                ->get();
        }
        return view('user.dashboard', compact(
            'user',
            'section',
            'parkings',
            'selectedParking',
            'availablePlaces',
            'places'
        ));
    }

    public function reserve(Request $request) {
        $placeId = $request->place_id;

        $place = Place::findOrFail($placeId);
         
        $vehicle = Vehicle::where('plate_number', $request->plate_number)->first();

        if (!$vehicle) {
            $vehicle = Vehicle::create([
                'plate_number' => $request->plate_number,
                'type' => $request->type
            ]);
        }

        $alreadyReserved = Reservation::where('vehicle_id', $vehicle->id)
            ->whereHas('place', function ($q) {
                $q->where('is_occupied', true);
            })
            ->exists();

        if ($alreadyReserved) {
            return back()->with('error', 'ce vehicule est deje reserve');
        }

        Reservation::create([
            'user_id' => auth()->id(),
            'place_id' => $placeId,
            'vehicle_id' => $vehicle->id,
            'reserved_at' => now(),
        ]);

        $place->update([
            'is_occupied' => true
        ]);

        return back()->with('success', 'Place réservée avec succès');
    }
}
