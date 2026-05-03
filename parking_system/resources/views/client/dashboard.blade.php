@extends('layouts.app')

@section('content')

    <div class="h-screen w-full flex flex-col lg:flex-row bg-slate-50 font-sans overflow-hidden">

        {{-- ================= LEFT ================= --}}
        <div
            class="w-full lg:w-[60%] h-screen overflow-y-auto p-6 md:p-12 lg:p-20
        [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden">

            <div class="max-w-4xl mx-auto">
                <div class="flex items-center gap-4 mb-12">

                    <div
                        class="w-12 h-12 bg-blue-600 text-white flex items-center justify-center rounded-2xl font-black shadow-lg shadow-blue-200">
                        C
                    </div>

                    <div>
                        <h1 class="text-2xl font-black text-slate-900 tracking-tight">
                            Client Panel
                        </h1>

                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">
                            Gestion Réservation
                        </p>
                    </div>

                </div>
                
                <div class="flex justify-between items-center mb-10">

                    <div class="flex gap-3 flex-wrap">

                        <a href="{{ route('client.dashboard', ['section' => 'home']) }}"
                            class="flex items-center gap-2 px-4 py-3 rounded-xl text-sm font-bold
            {{ $section == 'home'
                ? 'bg-blue-600 text-white shadow-lg shadow-blue-200'
                : 'bg-white text-slate-500 border border-slate-200' }}
            hover:scale-105 transition-all">

                            <span>🏠</span>
                            <span>Accueil</span>
                        </a>

                        <a href="{{ route('client.dashboard', ['section' => 'reservations']) }}"
                            class="flex items-center gap-2 px-4 py-3 rounded-xl text-sm font-bold
            {{ $section == 'reservations'
                ? 'bg-blue-600 text-white shadow-lg shadow-blue-200'
                : 'bg-white text-slate-500 border border-slate-200' }}
            hover:scale-105 transition-all">

                            <span>📄</span>
                            <span>Réservations</span>
                        </a>

                        <a href="{{ route('client.dashboard', ['section' => 'history']) }}"
                            class="flex items-center gap-2 px-4 py-3 rounded-xl text-sm font-bold
            {{ $section == 'history'
                ? 'bg-blue-600 text-white shadow-lg shadow-blue-200'
                : 'bg-white text-slate-500 border border-slate-200' }}
            hover:scale-105 transition-all">

                            <span>🕓</span>
                            <span>Historique</span>
                        </a>

                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            class="bg-slate-900 text-white px-5 py-3 rounded-xl text-sm font-bold hover:bg-red-600 transition-all">
                            Logout
                        </button>
                    </form>

                </div>

                {{-- HEADER --}}





                {{-- FLASH --}}
                @if (session('success'))
                    <div class="bg-green-100 text-green-700 p-4 rounded-xl mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-100 text-red-600 p-4 rounded-xl mb-6">
                        {{ session('error') }}
                    </div>
                @endif


                {{-- ================= HOME ================= --}}
                @if ($section === 'home')

                    <div class="bg-white p-6 rounded-2xl border mb-8">
                        <h2 class="text-2xl font-black mb-2">Réserver une place</h2>
                        <p class="text-slate-500 text-sm">
                            Choisissez un parking et réservez facilement.
                        </p>
                    </div>

                    {{-- PARKINGS --}}
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-8">
                        @foreach ($parkings as $p)
                            <a href="{{ route('client.dashboard', ['section' => 'home', 'parking' => $p->id]) }}"
                                class="p-4 rounded-xl text-center font-bold text-xs
                           {{ $selectedParking && $selectedParking->id == $p->id
                               ? 'bg-blue-600 text-white'
                               : 'bg-white border text-slate-500' }}">
                                {{ $p->name }}
                            </a>
                        @endforeach
                    </div>

                    {{-- DETAILS --}}
                    @if ($selectedParking)
                        <div class="bg-white rounded-2xl border p-6 mb-6">
                            <h3 class="font-black text-xl">{{ $selectedParking->name }}</h3>
                            <p class="text-slate-400 text-sm">{{ $selectedParking->address }}</p>

                            <div class="flex justify-between mt-4 text-sm">
                                <p>📞 {{ $selectedParking->phone }}</p>
                                <p>🕒 {{ $selectedParking->opening_hours }}</p>
                                <p>
                                    {{ $availablePlaces }} Places Libres
                                </p>
                                <p class="text-blue-600 font-bold">
                                    {{ $selectedParking->price }} DH/h
                                </p>
                            </div>
                        </div>

                        <button onclick="toggleReservation()"
                            class="bg-blue-600 text-white px-6 py-3 rounded-xl font-bold mb-6">
                            Réserver
                        </button>

                        {{-- FORM --}}
                        <div id="reservationBox" class="hidden bg-white p-6 rounded-2xl border">

                            <form method="POST" action="{{ route('client.reserve') }}" class="space-y-4">
                                @csrf

                                <input name="plate_number" placeholder="Plaque" class="w-full p-3 rounded-xl bg-slate-100"
                                    required>

                                <input name="marque" placeholder="Marque" class="w-full p-3 rounded-xl bg-slate-100"
                                    required>

                                <input type="date" name="reservation_date" class="w-full p-3 rounded-xl bg-slate-100"
                                    required>

                                <input type="time" name="reservation_time" class="w-full p-3 rounded-xl bg-slate-100"
                                    required>

                                <select name="place_id" class="w-full p-3 rounded-xl bg-slate-100" required>
                                    <option value="">-- Choisir place --</option>
                                    @foreach ($places as $place)
                                        <option value="{{ $place->id }}">
                                            Place {{ $place->number }}
                                        </option>
                                    @endforeach
                                </select>

                                <button class="w-full bg-green-600 text-white py-3 rounded-xl font-bold">
                                    Confirmer
                                </button>

                            </form>

                        </div>
                    @endif

                @endif


                {{-- ================= RESERVATIONS ================= --}}
                @if ($section === 'reservations')
                    <h2 class="text-2xl font-black mb-6">Mes Réservations</h2>

                    @forelse($reservations as $r)
                        <div class="bg-white p-6 rounded-2xl border mb-4">

                            <h3 class="font-black">
                                {{ $r->place->parking->name }}
                            </h3>

                            <p class="text-sm text-slate-400">
                                {{ $r->place->parking->address }}
                            </p>

                            <p class="text-sm mt-2">
                                🚗 {{ $r->vehicle->plate_number }}
                            </p>

                            <p class="text-sm text-slate-400">
                                📅 {{ $r->reservation_date }} - ⏰ {{ $r->reservation_time }}
                            </p>

                            <form method="POST" action="{{ route('client.reservation.cancel', $r->id) }}">
                                @csrf
                                <button class="text-red-500 text-sm mt-2">Annuler</button>
                            </form>

                        </div>
                    @empty
                        <div class="bg-white p-10 text-center rounded-2xl border">
                            Aucune réservation
                        </div>
                    @endforelse
                @endif


                {{-- ================= HISTORY ================= --}}
                @if ($section === 'history')
                    <h2 class="text-2xl font-black mb-6">Historique</h2>

                    @forelse($history as $r)
                        <div class="bg-white p-6 rounded-2xl border mb-4 shadow-sm">

                            <div class="flex justify-between">

                                <div class="space-y-2">

                                    <h3 class="font-black text-lg">
                                        {{ $r->place->parking->name }}
                                    </h3>

                                    <p class="text-xs text-slate-400">
                                        📍 {{ $r->place->parking->address }}
                                    </p>

                                    <p class="text-sm font-bold">
                                        🅿️ Place {{ $r->place->number }}
                                    </p>

                                    <p class="text-sm text-slate-500">
                                        🚗 {{ $r->vehicle->plate_number }}
                                        ({{ $r->vehicle->marque }})
                                    </p>

                                    <p class="text-sm text-slate-400">
                                        📅 {{ $r->reservation_date }}
                                        ⏰ {{ $r->reservation_time }}
                                    </p>

                                    <p class="text-sm font-bold text-blue-600">
                                        💰 {{ $r->place->parking->price }} DH/h
                                    </p>

                                </div>


                                <p class="text-xs text-slate-700 mt-3">
                                    {{ $r->created_at }}
                                </p>


                            </div>

                        </div>

                    @empty

                        <div class="bg-white p-10 text-center rounded-2xl border">
                            Aucun historique
                        </div>
                    @endforelse
                @endif

            </div>
        </div>


        {{-- ================= RIGHT IMAGE ================= --}}
        <div class="hidden lg:block w-[40%] h-screen bg-slate-900 relative">

            <img src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d?q=80&w=2070"
                class="absolute w-full h-full object-cover opacity-40">

            <div class="absolute bottom-10 left-10 text-white">
                <h2 class="text-4xl font-black">Client</h2>
                <p class="text-slate-300">Réservation simple</p>
            </div>

        </div>

    </div>

    <script>
        function toggleReservation() {
            document.getElementById('reservationBox').classList.toggle('hidden');
        }
    </script>

@endsection
