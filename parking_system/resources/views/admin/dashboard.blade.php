@extends('layouts.admin')

@section('content')

    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif


    {{-- ================= STATISTICS ================= --}}
    @if ($section == 'statistics')
        <h2 class="text-xl font-bold mb-6">Supervision Générale</h2>

        <div class="grid grid-cols-4 gap-6 mb-10">

            <div class="bg-white p-6 rounded shadow">
                <p class="text-xs">Total Places</p>
                <p class="text-2xl font-bold">{{ $totalPlaces }}</p>
            </div>

            <div class="bg-white p-6 rounded shadow">
                <p class="text-xs">Véhicules Actuels</p>
                <p class="text-2xl font-bold">{{ $vehiclesInside }}</p>
            </div>

            <div class="bg-white p-6 rounded shadow">
                <p class="text-xs">Places Libres</p>
                <p class="text-2xl font-bold">{{ $freePlaces }}</p>
            </div>

            <div class="bg-white p-6 rounded shadow">
                <p class="text-xs">Occupation</p>
                <p class="text-2xl font-bold">{{ $occupationRate }}%</p>
            </div>

        </div>

        <h2 class="text-xl font-bold mb-6">Activité Aujourd’hui</h2>

        <div class="grid grid-cols-2 gap-6">

            <div class="bg-white p-6 rounded shadow">
                <p class="text-xs">Entrées</p>
                <p class="text-2xl font-bold">{{ $entriesToday }}</p>
            </div>

            <div class="bg-white p-6 rounded shadow">
                <p class="text-xs">Sorties</p>
                <p class="text-2xl font-bold">{{ $exitsToday }}</p>
            </div>

        </div>
    @endif



    {{-- ================= USERS ================= --}}
    @if ($section == 'users')
        <h2 class="text-xl font-bold mb-6">Liste Utilisateurs</h2>

        <div class="bg-white rounded shadow overflow-hidden">

            <table class="w-full text-sm">

                <thead class="bg-slate-50">
                    <tr>
                        <th class="p-4 text-left">Nom</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y">

                    @foreach ($users as $user)
                        <tr>

                            <td class="p-4">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>

                            <td>
                                <span
                                    class="px-2 py-1 text-xs rounded
                {{ $user->role->name == 'admin'
                    ? 'bg-purple-100 text-purple-700'
                    : ($user->role->name == 'agent'
                        ? 'bg-blue-100 text-blue-700'
                        : 'bg-gray-100 text-gray-700') }}">
                                    {{ $user->role->name }}
                                </span>
                            </td>

                            <td class="text-center">
                                @if ($user->role->name == 'agent')
                                    <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}"
                                        onsubmit="return confirm('Confirmer suppression ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-500 hover:text-red-700">
                                            🗑
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-400 text-xs">—</span>
                                @endif
                            </td>

                        </tr>
                    @endforeach

                </tbody>

            </table>

        </div>
    @endif



    {{-- ================= PARKINGS ================= --}}
    @if ($section == 'parkings')
        <h2 class="text-xl font-bold mb-6 flex justify-between items-center">
            Liste Parkings

            <!-- Bouton Ajouter -->
            <button onclick="document.getElementById('addModal').classList.remove('hidden')"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                ➕ Ajouter
            </button>
        </h2>

        <div class="bg-white rounded shadow overflow-hidden">

            <table class="w-full text-sm">

                <thead class="bg-slate-50">
                    <tr>
                        <th class="p-4 text-left">Nom</th>
                        <th>Adresse</th>
                        <th>Places</th>
                        <th>Horaires</th>
                        <th>Prix Voiture</th>
                        <th>Prix Moto</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y">

                    @foreach ($parkings as $parking)
                        <tr>

                            <td class="p-4">{{ $parking->name }}</td>
                            <td>{{ $parking->address }}</td>
                            <td>{{ $parking->total_places }}</td>
                            <td>{{ $parking->opening_hours }}</td>
                            <td>{{ $parking->price_car }} DH</td>
                            <td>{{ $parking->price_motorcycle }} DH</td>
                            <td class="text-center space-x-2">

                                <!-- Modifier -->
                                <button
                                    onclick="openEdit({{ $parking->id }}, '{{ $parking->name }}', '{{ $parking->address }}', {{ $parking->total_places }}, '{{ $parking->opening_hours }}')"
                                    class="text-blue-600 hover:text-blue-800">
                                    ✏️
                                </button>

                                <!-- Supprimer -->
                                <form method="POST" action="{{ route('admin.parkings.destroy', $parking->id) }}"
                                    class="inline" onsubmit="return confirm('Confirmer suppression ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 hover:text-red-800">
                                        🗑
                                    </button>
                                </form>

                            </td>

                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    @endif

    <!-- MODAL ADD -->
    <div id="addModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">

        <div class="bg-white p-8 rounded shadow w-96">

            <h3 class="text-lg font-bold mb-4">Ajouter Parking</h3>

            <form method="POST" action="{{ route('admin.parkings.store') }}">
                @csrf

                <input name="name" placeholder="Nom" class="w-full border p-2 mb-3" required>
                <input name="address" placeholder="Adresse" class="w-full border p-2 mb-3" required>
                <input type="number" name="total_places" placeholder="Places" class="w-full border p-2 mb-3" required>
                <input name="opening_hours" placeholder="Horaires" class="w-full border p-2 mb-3" required>
                <input type="number" step="0.01" name="price_car" placeholder="Prix voiture / heure"
                    class="w-full border p-2 mb-3" required>
                <input type="number" step="0.01" name="price_motorcycle" placeholder="Prix moto / heure"
                    class="w-full border p-2 mb-3" required>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')"
                        class="px-4 py-2 bg-gray-300 rounded">Annuler</button>

                    <button class="px-4 py-2 bg-blue-600 text-white rounded">
                        Enregistrer
                    </button>
                </div>

            </form>
        </div>
    </div>
@endsection
