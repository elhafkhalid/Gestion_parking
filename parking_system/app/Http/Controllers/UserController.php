<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Parking;


class UserController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $section = $request->section ?? 'home';
        $parkings = Parking::all();
        $selectedParking = null;
        $availablePlaces = 0;

        
        if ($section === 'parkings' && $request->parking) {

            $selectedParking = Parking::with('places')
                ->find($request->parking);

            if ($selectedParking) {
                $availablePlaces = $selectedParking
                    ->places()
                    ->where('is_occupied', false)
                    ->count();
            }
        }

        return view('user.dashboard', compact(
            'user',
            'section',
            'parkings',
            'selectedParking',
            'availablePlaces'
        ));
    }
}
