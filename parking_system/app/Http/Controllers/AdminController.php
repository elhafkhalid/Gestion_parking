<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Parking;
use App\Models\Place;
use App\Models\ParkingRecord;
use App\Models\AgentRequest;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $section = $request->section ?? 'statistics';

        // ===== SUPERVISION =====
        $totalPlaces = Place::count();
        $vehiclesInside = ParkingRecord::whereNull('exit_time')->count();
        $freePlaces = $totalPlaces - $vehiclesInside;

        $occupationRate = $totalPlaces > 0
            ? round(($vehiclesInside / $totalPlaces) * 100)
            : 0;

        // ===== ACTIVITÉ =====
        $entriesToday = ParkingRecord::whereDate('entry_time', today())->count();
        $exitsToday = ParkingRecord::whereDate('exit_time', today())->count();

        // ===== UTILISATEURS =====
        $users = User::with('role')->latest()->get();
        $totalUsers = User::count();
        $totalAgents = User::whereHas('role', fn($q) => $q->where('name', 'agent'))->count();
        $pendingRequests = AgentRequest::where('status', 'pending')->count();

        // ===== PARKINGS =====
        $parkings = Parking::latest()->get();

        return view('admin.dashboard', compact(
            'section',
            'totalPlaces',
            'vehiclesInside',
            'freePlaces',
            'occupationRate',
            'entriesToday',
            'exitsToday',
            'users',
            'totalUsers',
            'totalAgents',
            'pendingRequests',
            'parkings'
        ));
    }

    public function destroyUser(User $user)
    {
        // Empêcher suppression de soi-même
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Impossible de supprimer votre propre compte.');
        }

        // Vérifier que c'est bien un agent
        if ($user->role->name !== 'agent') {
            return back()->with('error', 'Vous pouvez supprimer uniquement les agents.');
        }

        $user->delete();

        return back()->with('success', 'Agent supprimé avec succès.');
    }

    // AJOUT
    public function storeParking(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'address' => 'required',
            'total_places' => 'required|integer|min:1',
            'opening_hours' => 'required',
            'price_car' => 'required|numeric|min:0',
            'price_motorcycle' => 'required|numeric|min:0',
        ]);

        Parking::create($data);

        return back()->with('success', 'Parking ajouté avec succès.');
    }


    // MODIFICATION
    public function updateParking(Request $request, Parking $parking)
    {
        $data = $request->validate([
            'name' => 'required',
            'address' => 'required',
            'total_places' => 'required|integer|min:1',
            'opening_hours' => 'required',
            'price_car' => 'required|numeric|min:0',
            'price_motorcycle' => 'required|numeric|min:0',
        ]);

        $parking->update($data);

        return back()->with('success', 'Parking modifié.');
    }


    // SUPPRESSION
    public function destroyParking(Parking $parking)
    {
        // Sécurité : empêcher suppression si véhicules actifs
        if ($parking->places()->whereHas('parkingRecords', function ($q) {
            $q->whereNull('exit_time');
        })->exists()) {
            return back()->with('error', 'Impossible de supprimer : véhicules encore stationnés.');
        }

        $parking->delete();

        return back()->with('success', 'Parking supprimé.');
    }
}
