@extends('layouts.app')

@section('content')

    <div class="h-screen w-full flex flex-col lg:flex-row font-sans bg-slate-50 overflow-hidden">

        {{-- LEFT --}}
        <div
            class="w-full lg:w-[60%] h-full flex flex-col p-6 md:p-12 lg:p-20 overflow-y-auto
                [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden">

            <div class="max-w-4xl w-full mx-auto">

                {{-- HEADER --}}
                <div class="flex justify-between items-center mb-10">

                    <div class="flex items-center gap-4">
                        <div
                            class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center text-white font-black">
                            P
                        </div>
                        <div>
                            <h1 class="text-2xl font-black text-slate-900">ParkSys</h1>
                            <p class="text-xs text-slate-400 uppercase">Espace Client</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="bg-slate-900 text-white px-5 py-2 rounded-xl font-bold">
                            Logout
                        </button>
                    </form>

                </div>

                {{-- NAVIGATION --}}
                <div class="flex gap-2 bg-white p-2 rounded-2xl border border-slate-200 shadow-sm mb-10 w-fit">

                    <a href="{{ route('client.dashboard', ['section' => 'home']) }}"
                        class="px-5 py-2 rounded-xl font-bold text-sm
                   {{ $section == 'home' ? 'bg-blue-600 text-white' : 'text-slate-500 hover:bg-slate-100' }}">
                        🏠 Home
                    </a>

                    <a href="{{ route('client.dashboard', ['section' => 'reservations']) }}"
                        class="px-5 py-2 rounded-xl font-bold text-sm
                   {{ $section == 'reservations' ? 'bg-blue-600 text-white' : 'text-slate-500 hover:bg-slate-100' }}">
                        📄 Réservation actuel
                    </a>

                    <a href="{{ route('client.dashboard', ['section' => 'history']) }}"
                        class="px-5 py-2 rounded-xl font-bold text-sm
                    {{ $section == 'history' ? 'bg-blue-600 text-white' : 'text-slate-500 hover:bg-slate-100' }}">
                        🕓 Historique
                    </a>

                </div>

                {{-- MESSAGES --}}
                @if (session('success'))
                    <div class="bg-green-50 text-green-700 p-4 rounded-xl mb-6">
                        ✅ {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6">
                        ❌ {{ session('error') }}
                    </div>
                @endif

                {{-- ===================== --}}
                {{-- HOME SECTION --}}
                {{-- ===================== --}}
                @if ($section === 'home')

                    <div class="bg-white rounded-3xl p-8 border mb-10">
                        <h2 class="text-3xl font-black mb-3">Réserver une place</h2>
                        <p class="text-slate-500">
                            Choisissez un parking et réservez votre place en quelques secondes.
                        </p>
                    </div>

                    {{-- PARKINGS --}}
                    <h3 class="text-xl font-black mb-6">Choisir un parking</h3>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-10">
                        @foreach ($parkings as $p)
                            <a href="{{ route('client.dashboard', ['section' => 'home', 'parking' => $p->id]) }}"
                                class="p-4 rounded-2xl text-center border-2 font-bold text-xs
                           {{ $selectedParking && $selectedParking->id == $p->id
                               ? 'border-blue-600 bg-blue-50 text-blue-600'
                               : 'bg-white text-slate-500 hover:border-slate-200' }}">
                                {{ $p->name }}
                            </a>
                        @endforeach
                    </div>

                    {{-- DETAILS --}}
                    @if ($selectedParking)
                        <div class="bg-white rounded-3xl border shadow overflow-hidden mb-6">

                            <div class="bg-blue-600 p-8 text-white flex justify-between">
                                <div>
                                    <h3 class="text-2xl font-black">{{ $selectedParking->name }}</h3>
                                    <p>{{ $selectedParking->address }}</p>
                                </div>

                                <div
                                    class="bg-white text-blue-600 w-20 h-20 rounded-xl flex items-center justify-center font-black">
                                    {{ $availablePlaces }}
                                </div>
                            </div>

                            <div class="p-6 flex justify-between text-slate-600">
                                <p>📞 {{ $selectedParking->phone }}</p>
                                <p>🕒 {{ $selectedParking->opening_hours }}</p>
                                <p class="font-bold text-blue-600">
                                    🚗 {{ $selectedParking->price }} DH / heure
                                </p>
                            </div>

                        </div>

                        {{-- BUTTON --}}
                        <button onclick="toggleReservation()"
                            class="bg-blue-600 text-white px-6 py-3 rounded-xl font-bold mb-6">
                            Réserver maintenant
                        </button>

                        {{-- FORM --}}
                        <div id="reservationBox" class="hidden bg-white p-6 rounded-2xl border">

                            <form method="POST" action="{{ route('client.reserve') }}" class="space-y-4">
                                @csrf

                                <input type="text" name="plate_number" placeholder="Plaque"
                                    class="w-full p-3 rounded-xl bg-slate-100" required>

                                <input type="text" name="marque" placeholder="Marque"
                                    class="w-full p-3 rounded-xl bg-slate-100" required>

                                <input type="date" name="reservation_date" class="w-full p-3 rounded-xl bg-slate-100"
                                    required>

                                <input type="time" name="reservation_time" class="w-full p-3 rounded-xl bg-slate-100"
                                    required>

                                <select name="place_id" class="w-full p-3 rounded-xl bg-slate-100" required>
                                    <option value="">-- Choisir une place --</option>
                                    @foreach ($places as $place)
                                        <option value="{{ $place->id }}">
                                            Place {{ $place->number }}
                                        </option>
                                    @endforeach
                                </select>

                                <button class="w-full bg-green-600 text-white py-3 rounded-xl font-bold">
                                    Confirmer réservation
                                </button>

                            </form>

                        </div>
                    @endif

                    {{-- ===================== --}}
                    {{-- RESERVATIONS SECTION --}}
                    {{-- ===================== --}}
                @elseif($section === 'reservations')
                    <h2 class="text-3xl font-black mb-10">Mes Réservations</h2>

                    @forelse($reservations as $r)
                        <div class="bg-white p-6 rounded-3xl border mb-6 shadow-sm hover:shadow-md transition-all">

                            <div class="flex justify-between items-start gap-6">

                                {{-- INFOS --}}
                                <div class="space-y-2">

                                    {{-- PARKING --}}
                                    <h3 class="text-xl font-black text-slate-900">
                                        {{ $r->place->parking->name }}
                                    </h3>

                                    {{-- ADRESSE --}}
                                    <p class="text-sm text-slate-400">
                                        📍 {{ $r->place->parking->address }}
                                    </p>

                                    {{-- PLACE --}}
                                    <p class="text-sm font-bold text-slate-700">
                                        🅿️ Place {{ $r->place->number }}
                                    </p>

                                    {{-- VEHICULE --}}
                                    <p class="text-sm text-slate-500">
                                        🚗 {{ $r->vehicle->plate_number }}
                                        <span class="text-slate-400">({{ $r->vehicle->marque }})</span>
                                    </p>

                                    {{-- DATE / TIME --}}
                                    <p class="text-sm text-slate-400">
                                        📅 {{ $r->reservation_date }}
                                        ⏰ {{ $r->reservation_time }}
                                    </p>

                                    {{-- PRIX --}}
                                    <p class="text-sm font-bold text-blue-600">
                                        💰 {{ $r->place->parking->price }} DH / heure
                                    </p>

                                </div>

                                {{-- ACTION --}}
                                <div class="text-right flex flex-col justify-between h-full">

                                    <form method="POST" action="{{ route('client.reservation.cancel', $r->id) }}">
                                        @csrf
                                        <button class="mt-4 text-red-500 text-sm font-bold hover:underline">
                                            Annuler
                                        </button>
                                    </form>

                                </div>

                            </div>
                        </div>

                    @empty

                        <div class="bg-white p-12 rounded-3xl text-center border">
                            <p class="text-5xl mb-4 opacity-30">📭</p>
                            <p class="text-slate-400 font-bold">Aucune réservation</p>
                            <p class="text-sm text-slate-300 mt-2">
                                Réservez une place pour commencer
                            </p>
                        </div>
                    @endforelse
                @elseif($section === 'history')
                    <h2 class="text-3xl font-black mb-10">Historique</h2>

                    @forelse($history  as $r)
                        <div class="bg-white p-6 rounded-3xl border mb-4 flex justify-between items-center opacity-80">

                            <div>
                                <h3 class="font-black text-lg">
                                    {{ $r->place->parking->name }}
                                </h3>

                                <p class="text-sm text-slate-500">
                                    🚗 {{ $r->vehicle->plate_number }}
                                </p>

                                <p class="text-sm text-slate-400">
                                    📅 {{ $r->reservation_date }} - ⏰ {{ $r->reservation_time }}
                                </p>
                            </div>

                            <div class="text-right">

                                {{-- STATUS --}}
                                @if ($r->status == 'cancelled')
                                    <span class="text-red-500 font-bold">Annulée</span>
                                @elseif($r->status == 'completed')
                                    <span class="text-slate-500 font-bold">Terminée</span>
                                @endif

                            </div>

                        </div>

                    @empty

                        <div class="bg-white p-10 rounded-3xl text-center border">
                            <p class="text-slate-400">Aucun historique</p>
                        </div>
                    @endforelse



                @endif


            </div>
        </div>

        {{-- RIGHT IMAGE --}}
        <div class="hidden lg:block w-[40%] h-full bg-slate-900 relative">

            <img src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d?q=80&w=2070"
                class="absolute inset-0 w-full h-full object-cover opacity-50">

            <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent"></div>

            <div class="absolute bottom-10 left-10 text-white">
                <h2 class="text-4xl font-black">Réservation</h2>
                <p class="text-slate-300">Simple, rapide et efficace</p>
            </div>

        </div>

    </div>

    <script>
        function toggleReservation() {
            document.getElementById('reservationBox').classList.toggle('hidden');
        }
    </script>

@endsection
