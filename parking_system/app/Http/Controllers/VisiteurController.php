<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Parking;


class VisiteurController extends Controller
{

    public function index(Request $request)
    {

        $parkings = Parking::all();
        $selectedParking = null;
        $availablePlaces = 0;
        $selectedParkingId = $request->parking;
        

        if ($selectedParkingId) {

            $selectedParking = Parking::with('places')
                ->find($selectedParkingId);

            if ($selectedParking) {
                $availablePlaces = $selectedParking
                    ->places
                    ->where('is_occupied', false)
                    ->count();
            }
        }

        return view('visiteur.dashboard', compact(
            'parkings',
            'selectedParking',
            'selectedParkingId',
            'availablePlaces'
        ));
    }
}
