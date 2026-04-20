<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Parking;
use App\Models\Place;
use App\Models\ParkingRecord;
use App\Models\AgentRequest;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AdminController extends Controller
{
    public function index(Request $request)
    {
        $section = $request->section ?? 'statistics';

        $totalPlaces = Place::count();
        $vehiclesInside = ParkingRecord::whereNull('exit_time')->count();
        $freePlaces = $totalPlaces - $vehiclesInside;

        $occupation = $totalPlaces > 0
            ? (($vehiclesInside / $totalPlaces) * 100)
            : 0;

        $entriesToday = ParkingRecord::whereDate('entry_time', today())->count();
        $exitsToday = ParkingRecord::whereDate('exit_time', today())->count();

        $users = User::with('role')->get();
        $totalUsers = User::count();
        $totalAgents = User::where('name', 'agent')->count();
        $pendingRequests = AgentRequest::where('status', 'pending')->count();
        $agentRequests = AgentRequest::with('user')->where('status', 'pending')->latest()->get();
        $parkings = Parking::get();

        return view('admin.dashboard', compact(
            'section',
            'totalPlaces',
            'vehiclesInside',
            'freePlaces',
            'occupation',
            'entriesToday',
            'exitsToday',
            'users',
            'totalUsers',
            'totalAgents',
            'pendingRequests',
            'agentRequests',
            'parkings',

        ));
    }

    public function deleteUser(User $user)
    {
        $user->delete();
        return back()->with('success', 'agent supprime avec succes');
    }


    public function storeParking(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'address' => 'required',
            'total_places' => 'required|integer|min:1',
            'opening_hours' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'price_car' => 'required|min:0',
            'price_motorcycle' => 'required|min:0',
        ]);

        $parking = Parking::create($data);

        for ($i = 1; $i <= $data['total_places']; $i++) {
            $parking->places()->create([
                'number' => 'P-' . $i,
            ]);
        }

        return back()->with('success', 'parking ajoute avec succes');
    }

    public function updateParking(Request $request, Parking $parking)
    {
        $data = $request->validate([
            'name' => 'required',
            'address' => 'required',
            'opening_hours' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'price_car' => 'required|numeric|min:0',
            'price_motorcycle' => 'required|numeric|min:0',
        ]);

        $parking->update($data);

        return back()->with('success', 'parking modifie');
    }

    public function deleteParking(Parking $parking)
    {
        if ($parking->places()->whereHas('parkingRecords', function ($q) {
            $q->whereNull('exit_time');
        })->exists()) {
            return back()->with('error', 'impossible de supprimer : vehicules encore statione');
        }

        $parking->delete();

        return back()->with('success', 'parking supprime');
    }

    public function acceptAgent($id)
    {
        $request = AgentRequest::with('user')->findOrFail($id);
        if ($request->status !== 'pending') return back()->with('error', 'demande deja traite');
        $agentRole = Role::where('name', 'agent')->first();
        $user = $request->user;
        $user->role_id = $agentRole->id;
        $user->save();
        $request->status = 'accepeted';
        $request->save();
        return redirect()->route('admin.dashboard');
    }

    public function rejectAgent($id)
    {
        $request = AgentRequest::with('user')->findOrFail($id);
        if ($request->status !== 'pending') return back()->with('error', 'demande deja traite');
        $request->status = 'rejected';
        $request->save();
        return redirect()->route('admin.dashboard');
    }
}
