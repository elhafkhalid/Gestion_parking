<?php
namespace App\Http\Controllers;

use App\Models\AgentRequest;
use App\Models\Parking;
use App\Models\ParkingRecord;
use App\Models\Place;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $section = $request->section ?? 'statistics';

        $totalPlaces    = Place::count();
        $occupiedPlaces = ParkingRecord::whereNull('exit_time')->count();
        $freePlaces     = $totalPlaces - $occupiedPlaces;

        if ($totalPlaces > 0) {
            $occupation = ($occupiedPlaces / $totalPlaces) * 100;
        } else {
            $occupation = 0;
        }

        $currentVehicles = $occupiedPlaces;

        $lastEntry = ParkingRecord::latest('entry_time')->first();

        $lastExit = ParkingRecord::whereNotNull('exit_time')
            ->latest('exit_time')
            ->first();

        $todayRevenue = ParkingRecord::whereDate('exit_time', today())
            ->sum('total_price');

        $latestVehicles = ParkingRecord::with(['vehicle', 'place.parking'])
            ->latest('entry_time')
            ->take(3)
            ->get();

        $agentRequests = AgentRequest::with('user')
            ->where('status', 'pending')
            ->get();

        $users    = User::with('role')->get();
        $parkings = Parking::all();

        $pendingRequests = AgentRequest::where('status', 'pending')->count();

        $recordsNotActif = ParkingRecord::with(['vehicle'])
            ->whereNotNull('exit_time')
            ->get();

        $availableAgents = User::whereHas('role', function ($q) {
            $q->where('name', 'agent');
        })
            ->whereDoesntHave('parking')
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
            'availableAgents',
        ));
    }

    public function deleteUser(User $user)
    {
        $hasActiveRecords = $user->parkingRecords()
            ->whereNull('exit_time')
            ->exists();

        if ($hasActiveRecords) {
            return back()->with('error', 'imposible de supprimer ce agent il a des vehicule en cours');
        }

        $parking = Parking::where('agent_id', $user->id)->first();

        if ($parking) {
            $parking->agent_id = null;
            $parking->save();
        }

        $user->delete();

        return back()->with('success', 'aent supprime avec succes');
    }

    public function storeParking(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required',
            'address'       => 'required',
            'total_places'  => 'required|integer|min:1',
            'opening_hours' => 'required',
            'email'         => 'required',
            'phone'         => 'required',
            'price'         => 'required|min:0',
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
            'name'          => 'required',
            'address'       => 'required',
            'total_places'  => 'required',
            'opening_hours' => 'required',
            'email'         => 'required',
            'phone'         => 'required',
            'price'         => 'required|numeric|min:0',
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
        if ($parking->places()->whereHas('activeParkingRecords')->exists()) {
            return back()->with('error', 'impossible de supprimer : vehicules encore statione');
        }

        $parking->delete();

        return back()->with('success', 'parking supprime');
    }

    public function acceptAgent($id)
    {
        $request = AgentRequest::with('user')->findOrFail($id);
        if ($request->status !== 'pending') {
            return back()->with('error', 'demande deja traite');
        }

        $agentRole     = Role::where('name', 'agent')->first();
        $user          = $request->user;
        $user->role_id = $agentRole->id;
        $user->save();
        $request->status = 'accepeted';
        $request->save();
        return back()->with('success', 'demande accepter');
    }

    public function rejectAgent($id)
    {
        $request = AgentRequest::with('user')->findOrFail($id);
        if ($request->status !== 'pending') {
            return back()->with('error', 'demande deja traite');
        }

        $request->delete();
        return back()->with('error', 'demande refuser');
    }

    public function assignAgent(Request $request, Parking $parking)
    {
        $request->validate([
            'agent_id' => 'required|exists:users,id',
        ]);

        $parking->agent_id = $request->agent_id;
        $parking->save();

        return back()->with('success', 'Agent affecté avec succès');
    }

}
