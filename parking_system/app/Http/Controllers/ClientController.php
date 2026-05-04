<?php
namespace App\Http\Controllers;

use App\Models\Parking;
use App\Models\Place;
use App\Models\Reservation;
use App\Models\Vehicle;
use Illuminate\Http\Request;


class ClientController extends Controller
{
    public function index(Request $request)
    {
        $section = $request->section ?? 'home';

        $parkings        = Parking::all();
        $selectedParking = null;
        $places          = collect();
        $availablePlaces = 0;

        if ($request->parking) {
            $selectedParking = Parking::find($request->parking);

            if ($selectedParking) {
                $places          = $selectedParking->places()->where('is_occupied', false)->get();
                $availablePlaces = $places->count();
            }
        }

        $reservations = Reservation::with(['place.parking', 'vehicle'])
            ->where('user_id', auth()->id())
            ->whereNull('canceled_at')
            ->whereNull('confirmed_at')
            ->get();

        $history = Reservation::where('user_id', auth()->id())->get();

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
            'place_id'         => 'required|exists:places,id',
            'plate_number'     => 'required',
            'marque'           => 'required',
            'reservation_date' => 'required|date',
            'reservation_time' => 'required',
        ]);

        $alreadyReserved = Reservation::where('user_id', auth()->id())
            ->whereNull('canceled_at')
            ->whereNull('confirmed_at')
            ->exists();

        if ($alreadyReserved) {
            return back()->with('error', 'vous avez deja une reservation ');
        }


        $vehicle = Vehicle::firstOrCreate(
            ['plate_number' => $request->plate_number],
            ['marque' => $request->marque]
        );


        $vehicleAlreadyReserved = Reservation::where('vehicle_id', $vehicle->id)
            ->whereNull('canceled_at')
            ->whereNull('confirmed_at')
            ->exists();

            
        if ($vehicleAlreadyReserved) {
            return back()->with('error', 'ce vehicule a deja reserver');
        }

        $reservationDateTime = new \DateTime(
            $request->reservation_date . ' ' . $request->reservation_time
        );

        $now = new \DateTime();

        if ($reservationDateTime <= $now) {
            return back()->with('error', 'date et heure invalide');
        }

        Reservation::create([
            'user_id'          => auth()->id(),
            'place_id'         => $request->place_id,
            'vehicle_id'       => $vehicle->id,
            'reservation_date' => $request->reservation_date,
            'reservation_time' => $request->reservation_time,
        ]);

        $place = Place::find($request->place_id);

        $place->update([
            'is_occupied' => true,
        ]);

        return redirect()->route('client.dashboard')
            ->with('success', 'reservation reussie');
    }


    public function cancel($id)
    {
        $reservation = Reservation::where('user_id', auth()->id())
            ->findOrFail($id);

        $reservation->update([
            'canceled_at' => now(),
        ]);

        $reservation->place->update([
            'is_occupied' => false,
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
