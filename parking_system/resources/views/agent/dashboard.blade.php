@extends('layouts.app')

@section('content')

    <div class="min-h-screen w-full flex flex-col lg:flex-row bg-slate-50 font-sans">


        <div class="w-full lg:w-[60%] p-6 md:p-12 lg:p-20 overflow-y-auto">


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

                    <a href="{{ route('agent.dashboard', ['section' => 'history']) }}"
                        class="p-3 rounded-xl {{ $section == 'history' ? 'bg-blue-600 text-white' : 'bg-white border' }}">📜</a>
                </div>
            </div>


            @if (session('success'))
                <div class="bg-green-100 text-green-700 p-4 rounded-xl mb-6">
                    {{ session('success') }}
                </div>
            @endif


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
                                <p class="font-bold">
                                    {{ $lastEntryTime ?? '---' }}
                                </p>
                            </div>

                            <div>
                                <p class="text-xs text-slate-400">Dernière sortie</p>
                                <p class="font-bold">
                                    {{ $lastExitTime ?? '---' }}
                                </p>
                            </div>

                            <div>
                                <p class="text-xs text-slate-400">Aujourd’hui</p>
                                <p class="font-bold">
                                    {{ date('Y-m-d') }}
                                </p>
                            </div>

                        </div>
                    </div>


                    <div class="bg-slate-900 text-white p-8 rounded-3xl">
                        <p class="text-xs uppercase opacity-60">Revenu aujourd’hui</p>
                        <h2 class="text-5xl font-black mt-2">
                            {{ $todayRevenue }} DH
                        </h2>
                    </div>


                    <div class="bg-white rounded-2xl border overflow-hidden">
                        <h3 class="p-6 font-black">Dernières entrées</h3>

                        <table class="w-full">
                            <tr class="bg-slate-100 text-sm">
                                <th class="p-4">Plaque</th>
                                <th>ParkName</th>
                                <th>Place</th>
                                <th>Heure</th>
                            </tr>

                            @foreach ($latestVehicles as $v)
                                <tr class="text-center border-t">
                                    <td class="p-4">{{ $v->vehicle->plate_number }}</td>
                                    <td>{{ $v->place->parking->name }}</td>
                                    <td>{{ $v->place->number }}</td>
                                    <td>{{ $v->entry_time }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>

                </div>
            @endif


            @if ($section == 'entry')
                <div class="bg-white p-8 rounded-3xl border max-w-xl">

                    <h2 class="text-2xl font-black mb-6">Entrée Véhicule</h2>

                    @if (session('error'))
                        <div class="bg-red-100 text-red-600 p-3 rounded-xl mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="bg-green-100 text-green-600 p-3 rounded-xl mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('agent.entry') }}" class="space-y-5">
                        @csrf

                        {{-- PARKING --}}
                        <div>
                            <label class="text-xs font-bold text-slate-400">Parking</label>
                            <select id="parkingSelect" name="parking_id" class="w-full p-4 rounded-xl bg-slate-100"
                                required>
                                <option value="">-- Choisir parking --</option>
                                @foreach ($parkings as $parking)
                                    <option value="{{ $parking->id }}">{{ $parking->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- PLACE --}}
                        <div>
                            <label class="text-xs font-bold text-slate-400">Place</label>
                            <select id="placeSelect" name="place_id" class="w-full p-4 rounded-xl bg-slate-100" required>
                                <option>Choisir un parking d'abord</option>
                            </select>
                        </div>

                        
                        <div>
                            <label class="text-xs font-bold text-slate-400">Plaque</label>
                            <input name="plate_number" placeholder="Ex: 123-ABC" class="w-full p-4 rounded-xl bg-slate-100"
                                required>
                        </div>

                        
                        <div>
                            <label class="text-xs font-bold text-slate-400">Marque</label>
                            <input class="w-full p-4 rounded-xl bg-slate-100" type="text" name="marque" placeholder="Marque" required >
                        </div>

                        <button class="w-full bg-blue-600 text-white py-4 rounded-xl font-bold">
                            Enregistrer Entrée
                        </button>

                    </form>
                </div>
            @endif

            @if ($section == 'exit')
                <table class="w-full bg-white rounded-2xl overflow-hidden">
                    <tr class="bg-slate-100">
                        <th class="p-4">Plaque</th>
                        <th>ParkName</th>
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
                                    <button type="submit" class="text-red-500 hover:text-red-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 inline" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1m0-10V7" />
                                        </svg>
                                    </button>
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

        {{-- IMAGE DROITE --}}
        <div class="hidden lg:block w-[40%] bg-slate-900 relative">
            <img src="{{ asset('images/agent.png') }}" class="absolute w-full h-full object-cover opacity-40">

            <div class="absolute bottom-10 left-10 text-white">
                <h2 class="text-4xl font-black">Agent Parking</h2>
                <p class="text-slate-300">Gestion en temps réel des véhicules</p>
            </div>
        </div>

    </div>
    'demandes' => asset('images/agent.png'),
    <script>
        document.getElementById('parkingSelect').addEventListener('change', function() {

            let parkingId = this.value;
            //console.log("Parking ID:", parkingId);
            let placeSelect = document.getElementById('placeSelect');
            fetch('/agent/places/' + parkingId)

                .then(res => res.json())
                .then(daata => {

                    placeSelect.innerHTML = '';
                    daata.forEach(place => {
                        placeSelect.innerHTML +=
                            `<option value="${place.id}">
                        Place ${place.number}
                    </option>`;
                    });

                });
        });
    </script>

@endsection
