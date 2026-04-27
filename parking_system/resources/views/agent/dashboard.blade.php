@extends('layouts.app')

@section('content')

    <div class="h-screen w-full flex bg-slate-50 font-sans overflow-hidden">

        {{-- CONTENU (SCROLL) --}}
        <div
            class="w-[60%] p-6 md:p-12 lg:p-20 overflow-y-auto 
                [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden">

            {{-- HEADER --}}
            <div class="flex justify-between items-center mb-12">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-600 text-white flex items-center justify-center rounded-2xl font-black">
                        A
                    </div>
                    <div>
                        <h1 class="text-2xl font-black">Agent Panel</h1>
                        <p class="text-xs text-slate-400 uppercase">Gestion Parking</p>
                    </div>
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('agent.dashboard', ['section' => 'dashboard']) }}"
                        class="p-3 rounded-xl {{ $section == 'dashboard' ? 'bg-blue-600 text-white' : 'bg-white border' }}">📊</a>

                    <a href="{{ route('agent.dashboard', ['section' => 'entry']) }}"
                        class="p-3 rounded-xl {{ $section == 'entry' ? 'bg-blue-600 text-white' : 'bg-white border' }}">🚗</a>

                    <a href="{{ route('agent.dashboard', ['section' => 'exit']) }}"
                        class="p-3 rounded-xl {{ $section == 'exit' ? 'bg-blue-600 text-white' : 'bg-white border' }}">🚪</a>

                    <a href="{{ route('agent.dashboard', ['section' => 'places']) }}"
                        class="p-3 rounded-xl {{ $section == 'places' ? 'bg-blue-600 text-white' : 'bg-white border' }}">🅿️</a>

                    <a href="{{ route('agent.dashboard', ['section' => 'reservations']) }}"
                        class="p-3 rounded-xl {{ $section == 'reservations' ? 'bg-blue-600 text-white' : 'bg-white border' }}">
                        📅
                    </a>

                    <a href="{{ route('agent.dashboard', ['section' => 'history']) }}"
                        class="p-3 rounded-xl {{ $section == 'history' ? 'bg-blue-600 text-white' : 'bg-white border' }}">📜</a>
                </div>
            </div>

            {{-- ALERTS --}}
            @if (session('success'))
                <div class="bg-green-100 text-green-700 p-4 rounded-xl mb-6">
                    {{ session('success') }}
                </div>
            @endif

              @if (session('error'))
                <div class="bg-red-100 text-red-700 p-4 rounded-xl mb-6">
                    {{ session('error') }}
                </div>
            @endif

            {{-- DASHBOARD --}}
            @if ($section == 'dashboard')
                <div class="space-y-8">

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-white p-6 rounded-2xl border">
                            <p class="text-xs text-slate-400">Places libres</p>
                            <h2 class="text-3xl font-black text-green-600">{{ $freePlaces }}</h2>
                        </div>

                        <div class="bg-white p-6 rounded-2xl border">
                            <p class="text-xs text-slate-400">Occupées</p>
                            <h2 class="text-3xl font-black text-red-500">{{ $occupiedPlaces }}</h2>
                        </div>

                        <div class="bg-white p-6 rounded-2xl border">
                            <p class="text-xs text-slate-400">Total</p>
                            <h2 class="text-3xl font-black">{{ $totalPlaces }}</h2>
                        </div>

                        <div class="bg-white p-6 rounded-2xl border">
                            <p class="text-xs text-slate-400">Véhicules présents</p>
                            <h2 class="text-3xl font-black text-blue-600">{{ $currentVehicles }}</h2>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-2xl border">
                        <h3 class="font-black mb-4">Activité</h3>

                        <div class="grid grid-cols-3 gap-4 text-center">
                            <div>
                                <p class="text-xs text-slate-400">Dernière entrée</p>
                                <p class="font-bold">{{ $lastEntryTime ?? '---' }}</p>
                            </div>

                            <div>
                                <p class="text-xs text-slate-400">Dernière sortie</p>
                                <p class="font-bold">{{ $lastExitTime ?? '---' }}</p>
                            </div>

                            <div>
                                <p class="text-xs text-slate-400">Aujourd’hui</p>
                                <p class="font-bold">{{ date('Y-m-d') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-900 text-white p-8 rounded-3xl">
                        <p class="text-xs uppercase opacity-60">Revenu aujourd’hui</p>
                        <h2 class="text-5xl font-black mt-2">{{ $todayRevenue }} DH</h2>
                    </div>

                </div>
            @endif

            {{-- ENTRY --}}
            @if ($section == 'entry')
                <div class="bg-white p-8 rounded-3xl border max-w-xl">

                    <h2 class="text-2xl font-black mb-6">Entrée Véhicule</h2>

                    <form method="POST" action="{{ route('agent.entry') }}" class="space-y-5">
                        @csrf

                        <select id="parkingSelect" name="parking_id" class="w-full p-4 rounded-xl bg-slate-100" required>
                            <option value="">-- Choisir parking --</option>
                            @foreach ($parkings as $parking)
                                <option value="{{ $parking->id }}">{{ $parking->name }}</option>
                            @endforeach
                        </select>

                        <select id="placeSelect" name="place_id" class="w-full p-4 rounded-xl bg-slate-100" required>
                            <option>Choisir un parking d'abord</option>
                        </select>

                        <input name="plate_number" placeholder="Plaque" class="w-full p-4 rounded-xl bg-slate-100" required>
                        <input name="marque" placeholder="Marque" class="w-full p-4 rounded-xl bg-slate-100" required>

                        <button class="w-full bg-blue-600 text-white py-4 rounded-xl font-bold">
                            Enregistrer Entrée
                        </button>

                    </form>
                </div>
            @endif

            {{-- EXIT --}}
            @if ($section == 'exit')
                <table class="w-full bg-white rounded-2xl overflow-hidden">
                    <tr class="bg-slate-100">
                        <th class="p-4">Plaque</th>
                        <th>Parking</th>
                        <th>Place</th>
                        <th>Action</th>
                    </tr>

                    @foreach ($recordsActif as $r)
                        <tr class="text-center border-t">
                            <td class="p-4">{{ $r->vehicle->plate_number }}</td>
                            <td>{{ $r->place->parking->name }}</td>
                            <td>{{ $r->place->number }}</td>
                            <td>
                                <form method="POST" action="{{ route('agent.exit', $r->id) }}">
                                    @csrf
                                    <button class="text-red-500">🚪</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </table>
            @endif

            {{-- PLACES --}}
            @if ($section == 'places')
                <div class="grid grid-cols-4 gap-4">
                    @foreach ($places as $place)
                        <div
                            class="p-6 rounded-2xl text-center 
                {{ $place->is_occupied ? 'bg-red-100' : 'bg-green-100' }}">
                            <p class="font-bold">{{ $place->parking->name }}</p>
                            <h3>Place {{ $place->number }}</h3>
                            <p>{{ $place->is_occupied ? 'Occupée' : 'Libre' }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
            {{-- RESERVATIONS --}}

            @if ($section == 'reservations')
                <div class="bg-white rounded-2xl overflow-hidden">

                    <table class="w-full">
                        <tr class="bg-slate-100 text-sm">
                            <th class="p-4">Client</th>
                            <th>Véhicule</th>
                            <th>Parking</th>
                            <th>Place</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>

                        @forelse ($reservations as $r)
                            <tr class="text-center border-t">
                                <td class="p-4">{{ $r->user->name }}</td>
                                <td>{{ $r->vehicle->plate_number }}</td>
                                <td>{{ $r->place->parking->name }}</td>
                                <td>{{ $r->place->number }}</td>
                                <td>
                                    {{ $r->reservation_date }} <br>
                                    <span class="text-xs text-slate-400">{{ $r->reservation_time }}</span>
                                </td>

                                <td class="flex justify-center gap-2 p-2">

                                    
                                    <form method="POST" action="{{ route('agent.confirm.reservation', $r->id) }}">
                                        @csrf
                                        <button class="bg-green-500 text-white px-3 py-1 rounded-xl">
                                            ✅
                                        </button>
                                    </form>

                                   
                                    <form method="POST" action="{{ route('agent.cancel.reservation', $r->id) }}">
                                        @csrf
                                        <button class="bg-red-500 text-white px-3 py-1 rounded-xl">
                                            ❌
                                        </button>
                                    </form>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center p-6 text-slate-400">
                                    Aucune réservation
                                </td>
                            </tr>
                        @endforelse

                    </table>

                </div>
            @endif
            {{-- HISTORY --}}
            @if ($section == 'history')
                <table class="w-full bg-white rounded-2xl overflow-hidden">
                    <tr class="bg-slate-100">
                        <th class="p-4">Plaque</th>
                        <th>Entrée</th>
                        <th>Sortie</th>
                        <th>Prix</th>
                    </tr>

                    @foreach ($recordsNotActif as $r)
                        <tr class="text-center border-t">
                            <td class="p-4">{{ $r->vehicle->plate_number }}</td>
                            <td>{{ $r->entry_time }}</td>
                            <td>{{ $r->exit_time }}</td>
                            <td>{{ $r->total_price }}</td>
                        </tr>
                    @endforeach
                </table>
            @endif

        </div>

        {{-- IMAGE FIXE --}}
        <div class="w-[40%] h-screen sticky top-0 bg-slate-900 relative overflow-hidden">

            <img src="{{ asset('images/agent.png') }}" class="absolute inset-0 w-full h-full object-cover opacity-40">

            <div class="absolute bottom-10 left-10 text-white">
                <h2 class="text-4xl font-black">Agent Parking</h2>
                <p class="text-slate-300">Gestion en temps réel des véhicules</p>
            </div>

        </div>

    </div>

    {{-- SCRIPT JS --}}
    <script>
        document.getElementById('parkingSelect')?.addEventListener('change', function() {

            let parkingId = this.value;
            let placeSelect = document.getElementById('placeSelect');

            fetch('/agent/places/' + parkingId)
                .then(res => res.json())
                .then(data => {
                    placeSelect.innerHTML = '';
                    data.forEach(place => {
                        placeSelect.innerHTML += `<option value="${place.id}">
                    Place ${place.number}
                </option>`;
                    });
                });
        });
    </script>

@endsection
