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
        $occupiedPlaces = ParkingRecord::whereNull('exit_time')->count();
        $freePlaces = $totalPlaces - $occupiedPlaces;


        $occupation = $totalPlaces > 0
            ? round(($occupiedPlaces / $totalPlaces) * 100)
            : 0;


        $currentVehicles = $occupiedPlaces;


        $lastEntry = ParkingRecord::latest('entry_time')->first();


        $lastExit = ParkingRecord::whereNotNull('exit_time')
            ->latest('exit_time')
            ->first();


        $todayRevenue = ParkingRecord::whereDate('exit_time', today())
            ->sum('total_price');


        $latestVehicles = ParkingRecord::with(['vehicle', 'place.parking'])
            ->latest('entry_time')
            ->take(5)
            ->get();

        $agentRequests = AgentRequest::with('user')
            ->where('status', 'pending')
            ->latest()
            ->get();

        $users = User::with('role')->get();
        $parkings = Parking::all();
        $pendingRequests = AgentRequest::where('status', 'pending')->count();

        $recordsNotActif = ParkingRecord::with(['vehicle', 'place'])
            ->whereNotNull('exit_time')
            ->get();


        return view('admin.dashboard', compact(
            'section',
            'totalPlaces',
            'occupiedPlaces',
            'freePlaces',
            'occupation',
            'currentVehicles',
            'lastEntry',
            'lastExit',
            'todayRevenue',
            'latestVehicles',
            'pendingRequests',
            'agentRequests',
            'users',
            'parkings',
            'recordsNotActif',
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
            'price' => 'required|min:0',
        ]);

        $parking = Parking::create($data);

        for ($i = 1; $i <= $data['total_places']; $i++) {
            $parking->places()->create([
                'number' => 'P-' . $i,
            ]);
        }

        return back()->with('success', 'parking ajoute avec succe');
    }

    public function updateParking(Request $request, Parking $parking)
    {
        $data = $request->validate([
            'name' => 'required',
            'address' => 'required',
            'total_places' => 'required',
            'opening_hours' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'price' => 'required|numeric|min:0',
        ]);

        $oldTotal = $parking->total_places;
        $newTotal = $data['total_places'];

        if ($newTotal > $oldTotal) {
            for ($i = $oldTotal + 1; $i <= $newTotal; $i++) {
                $parking->places()->create([
                    'number' => 'P-' . $i,
                ]);
            }
        }

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
        $request->delete();
        return redirect()->route('admin.dashboard');
    }
}
