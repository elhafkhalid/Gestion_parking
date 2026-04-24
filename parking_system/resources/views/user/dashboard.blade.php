@extends('layouts.app')

@section('content')
    <div class="min-h-screen w-full flex flex-col lg:flex-row font-sans antialiased bg-slate-50">

        {{-- ZONE DE CONTENU (GAUCHE - 60%) --}}
        <div class="w-full lg:w-[60%] flex flex-col p-6 md:p-12 lg:p-20 overflow-y-auto">
            <div class="max-w-4xl w-full mx-auto">

                {{-- HEADER & NAVIGATION --}}
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-12 gap-6">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center text-white text-xl font-black shadow-lg shadow-blue-200">
                            P
                        </div>
                        <div>
                            <h1 class="text-2xl font-black text-slate-900 tracking-tight">PARKSYS</h1>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Espace Utilisateur</p>
                        </div>
                    </div>

                    {{-- MENU NAV RAPIDE --}}
                    <nav class="flex gap-2 bg-white p-2 rounded-2xl border border-slate-200 shadow-sm">
                        @foreach (['home' => '🏠', 'parkings' => '🅿️'] as $key => $icon)
                            <a href="{{ route('user.dashboard', ['section' => $key]) }}"
                                class="p-3 rounded-xl transition-all {{ $section === $key ? 'bg-blue-600 text-white shadow-md shadow-blue-100' : 'text-slate-400 hover:bg-slate-50' }}">
                                <span class="text-lg">{{ $icon }}</span>
                            </a>
                        @endforeach
                    </nav>
                </div>

                @if (session('success'))
                    <div
                        class="bg-green-50 text-green-700 p-4 rounded-2xl border border-green-100 mb-8 flex items-center gap-3 animate-fade-in">
                        <span class="text-xl">✅</span> {{ session('success') }}
                    </div>
                @endif

                {{-- CONTENU DYNAMIQUE SELON LA SECTION --}}
                <main class="animate-fade-in">

                    @if ($section === 'home')
                        <div class="space-y-8">
                            <div class="bg-white rounded-[2.5rem] p-10 border border-slate-200 shadow-sm">
                                <h3 class="text-4xl font-black text-slate-900 mb-4 leading-tight">
                                    Ravi de vous revoir, <br><span class="text-blue-600">{{ $user->name }}</span>
                                </h3>
                                <p class="text-slate-500 text-lg max-w-md mb-8">
                                    Prêt à trouver votre place ? Visualisez la disponibilité en temps réel sur nos
                                    différents sites.
                                </p>
                                <div class="flex flex-wrap gap-4">
                                    <a href="{{ route('user.dashboard', ['section' => 'parkings']) }}"
                                        class="bg-slate-900 text-white px-8 py-4 rounded-2xl font-bold hover:bg-blue-600 transition-all">
                                        Explorer les Parkings →
                                    </a>
                                    <a href="{{ route('user.agent.create') }}"
                                        class="bg-white border border-slate-200 text-slate-900 px-8 py-4 rounded-2xl font-bold hover:bg-slate-50 transition-all">
                                        Devenir agent
                                    </a>
                                </div>
                            </div>
                        </div>
                    @elseif ($section === 'parkings')
                        <h2 class="text-3xl font-black text-slate-900 mb-8 tracking-tighter">Nos Parkings</h2>

                        {{-- LISTE PARKINGS --}}
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-10">
                            @foreach ($parkings as $p)
                                <a href="{{ route('user.dashboard', ['section' => 'parkings', 'parking' => $p->id]) }}"
                                    class="p-4 rounded-2xl text-center border-2 font-bold text-xs transition-all
                       {{ isset($selectedParking) && $selectedParking->id == $p->id
                           ? 'border-blue-600 bg-blue-50 text-blue-600'
                           : 'border-white bg-white text-slate-500 hover:border-slate-200' }}">
                                    {{ $p->name }}
                                </a>
                            @endforeach
                        </div>

                        @if ($selectedParking)
                            {{-- CARD PARKING --}}
                            <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-xl overflow-hidden mb-6">

                                <div class="bg-blue-600 p-10 text-white flex justify-between items-center">
                                    <div>
                                        <h3 class="text-3xl font-black">{{ $selectedParking->name }}</h3>
                                        <p>{{ $selectedParking->address }}</p>
                                    </div>

                                    <div
                                        class="bg-white text-blue-600 w-24 h-24 rounded-2xl flex items-center justify-center">
                                        <span class="text-3xl font-black">{{ $availablePlaces }}</span>
                                    </div>
                                </div>

                                <div class="p-8">
                                    <p class="text-slate-500">📞 {{ $selectedParking->phone }}</p>
                                    <p class="text-slate-500">🕒 {{ $selectedParking->opening_hours }}</p>
                                </div>

                            </div>
                            {{-- TARIFS --}}
                            <div class="bg-slate-900 text-white p-6 rounded-2xl mb-6">

                                <h3 class="text-lg font-bold mb-4">Tarifs</h3>

                                <div class="grid grid-cols-2 gap-4 text-center">

                                    <div class="bg-slate-800 p-4 rounded-xl">
                                        <p class="text-2xl">🚗</p>
                                        <p class="text-xl font-black mt-2">
                                            {{ $selectedParking->price_car }} DH
                                        </p>
                                        <p class="text-xs text-slate-400">Voiture / heure</p>
                                    </div>

                                    <div class="bg-slate-800 p-4 rounded-xl">
                                        <p class="text-2xl">🏍️</p>
                                        <p class="text-xl font-black mt-2">
                                            {{ $selectedParking->price_motorcycle }} DH
                                        </p>
                                        <p class="text-xs text-slate-400">Moto / heure</p>
                                    </div>

                                </div>
                            </div>
                            {{-- BOUTON RESERVER --}}
                            <div class="mb-6">
                                <button onclick="toggleReservation()"
                                    class="bg-blue-600 text-white px-6 py-3 rounded-xl font-bold">
                                    Réserver une place
                                </button>
                            </div>

                            {{-- LISTE DES PLACES (CACHÉ PAR DÉFAUT) --}}
                            <div id="reservationBox" class="hidden bg-white p-6 rounded-2xl border">

                                <h3 class="font-bold mb-4">Choisir une place libre</h3>

                                <form method="POST" action="{{ route('user.reserve') }}">
                                    @csrf

                                    <input type="hidden" name="parking_id" value="{{ $selectedParking->id }}">

                                    <div>
                                        <label class="text-xs font-bold text-slate-400">Plaque</label>
                                        <input type="text" name="plate_number"
                                            class="w-full p-3 rounded-xl bg-slate-100 mb-4" placeholder="Ex: 12345-A-6"
                                            required>
                                    </div>

                                    <div>
                                        <label class="text-xs font-bold text-slate-400">Type</label>
                                        <select name="type" class="w-full p-3 rounded-xl bg-slate-100 mb-4" required>
                                            <option value="">-- Choisir type --</option>
                                            <option value="car">Voiture</option>
                                            <option value="motorcycle">Moto</option>
                                        </select>
                                    </div>

                                    <select name="place_id" class="w-full p-3 rounded-xl bg-slate-100 mb-4" required>
                                        <option value="">-- Choisir une place --</option>

                                        @foreach ($selectedParking->places->where('is_occupied', false) as $place)
                                            <option value="{{ $place->id }}">
                                                Place {{ $place->number }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <button class="w-full bg-green-600 text-white py-3 rounded-xl">
                                        Confirmer réservation
                                    </button>
                                </form>
                            </div>
                        @endif
                    @endif


                </main>
            </div>
        </div>

        {{-- BARRE D'IMAGE FIXE (DROITE - 40%) --}}
        <div class="relative w-full lg:w-[40%] h-80 lg:h-screen lg:sticky lg:top-0 bg-slate-900 overflow-hidden">
            <img src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d?q=80&w=2070"
                class="absolute inset-0 w-full h-full object-cover opacity-50 grayscale hover:grayscale-0 transition-all duration-700">

            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-transparent to-transparent"></div>

            <div class="absolute inset-0 flex flex-col justify-end p-10 md:p-16 text-white">
                <span class="w-12 h-1.5 bg-blue-500 mb-6 rounded-full"></span>
                <h2 class="text-6xl font-black tracking-tighter uppercase leading-[0.85] mb-4">
                    {{ $section == 'home' ? 'Welcome' : $section }} <br>
                    <span class="text-blue-400 italic">Experience</span>
                </h2>
                <p class="text-slate-300 text-lg font-medium max-w-xs leading-relaxed opacity-80">
                    L'accès simplifié au stationnement urbain, intelligent et instantané.
                </p>
            </div>
        </div>

        <script>
            function toggleReservation() {
                let box = document.getElementById('reservationBox');
                box.classList.toggle('hidden');
            }
        </script>

    </div>
@endsection
