@extends('layouts.app')

@section('content')
    <div class="min-h-screen w-full flex flex-col lg:flex-row font-sans antialiased bg-slate-50">

        <div class="relative w-full lg:w-[40%] h-80 lg:h-screen lg:sticky lg:top-0 bg-indigo-900 overflow-hidden">
            <img src="https://images.unsplash.com/photo-1512428559087-560fa5ceab42?q=80&w=2070&auto=format&fit=crop"
                alt="Visiteur" class="absolute inset-0 w-full h-full object-cover opacity-50">

            <div class="absolute inset-0 bg-gradient-to-t from-indigo-950 via-transparent to-transparent"></div>

            <div class="absolute inset-0 flex flex-col justify-end p-10 md:p-16 text-white">
                <span class="w-12 h-1.5 bg-indigo-500 mb-6 rounded-full"></span>
                <h2 class="text-5xl font-black tracking-tighter uppercase leading-[0.9] mb-4">
                    Mobilité <br><span class="text-indigo-400 italic">Connectée</span>
                </h2>
                <p class="text-slate-300 text-lg font-medium max-w-xs leading-relaxed">
                    Trouvez votre place en un clic et simplifiez vos déplacements urbains.
                </p>
            </div>
        </div>

        <div class="w-full lg:w-[60%] flex flex-col p-6 md:p-12 lg:p-20 overflow-y-auto">

            <div class="max-w-4xl w-full mx-auto">

                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-16 gap-6">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-12 h-12 bg-indigo-600 rounded-2xl flex items-center justify-center text-white text-xl font-black shadow-lg shadow-indigo-200">
                            P
                        </div>
                        <div>
                            <h1 class="text-2xl font-black text-slate-900 tracking-tight">ParkSys</h1>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Dashboard Visiteur
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('login') }}"
                        class="inline-flex items-center gap-2 bg-slate-900 text-white px-8 py-4 rounded-2xl font-bold text-xs uppercase tracking-widest hover:bg-indigo-600 transition-colors shadow-xl shadow-slate-200">
                        Se connecter
                    </a>
                </div>

                <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">

                    <aside class="xl:col-span-4">
                        <div class="bg-white rounded-[2rem] border border-slate-200 p-6 shadow-sm">
                            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6 px-2">
                                Sélectionner un parking</h3>
                            <div class="space-y-2 max-h-[400px] overflow-y-auto">
                                @foreach ($parkings as $parking)
                                    @php
                                        $isSelected = $selectedParkingId == $parking->id;
                                    @endphp

                                    <a href="{{ route('visiteur.dashboard', ['parking' => $parking->id]) }}"
                                        class="group block p-4 rounded-xl transition-all {{ $isSelected ? 'bg-indigo-50 border-2 border-indigo-600' : 'bg-slate-50 border-2 border-transparent hover:border-slate-200' }}">

                                        <p
                                            class="font-black text-xs {{ $isSelected ? 'text-indigo-700' : 'text-slate-700' }}">
                                            {{ $parking->name }}
                                        </p>

                                        <p class="text-[9px] text-slate-400 mt-1 font-bold uppercase ">
                                            {{ $parking->address }}
                                        </p>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </aside>

                    <main class="xl:col-span-8">
                        @if ($selectedParking)
                            <div class="space-y-6">

                                <div
                                    class="bg-white rounded-[2.5rem] border border-slate-200 p-10 shadow-sm relative overflow-hidden">
                                    <div class="flex flex-col md:flex-row justify-between items-center gap-8">
                                        <div class="text-center md:text-left">
                                            <h2 class="text-4xl font-black text-slate-900 tracking-tighter leading-none">
                                                {{ $selectedParking->name }}</h2>
                                            <p
                                                class="text-slate-400 mt-4 font-medium flex items-center justify-center md:justify-start gap-2">
                                                {{ $selectedParking->address }}
                                            </p>

                                            <p
                                                class="text-slate-400 mt-4 font-medium flex items-center justify-center md:justify-start gap-2">

                                                {{ $selectedParking->email }}
                                            </p>
                                        </div>

                                        <div
                                            class="bg-indigo-600 rounded-3xl p-8 text-white text-center min-w-[160px] shadow-2xl shadow-indigo-100 transform -rotate-2">
                                            <span class="text-[10px] font-black uppercase opacity-60">Places Libres</span>
                                            <p class="text-6xl font-black mt-2 leading-none tracking-tighter">
                                                {{ $availablePlaces }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-white p-6 rounded-3xl border border-slate-200">
                                        <p class="text-[10px] font-black text-slate-400 uppercase mb-2">Horaires</p>
                                        <p class="font-extrabold text-slate-800 tracking-tight">
                                            {{ $selectedParking->opening_hours }}</p>
                                    </div>
                                    <div class="bg-white p-6 rounded-3xl border border-slate-200">
                                        <p class="text-[10px] font-black text-slate-400 uppercase mb-2">Phone</p>
                                        <p class="font-extrabold text-slate-800 tracking-tight">
                                            {{ $selectedParking->phone }}</p>
                                    </div>
                                </div>

                                <div class="bg-slate-900 rounded-[2.5rem] p-8 md:p-12 text-white">
                                    <div class="grid grid-cols-2 gap-8 divide-x divide-slate-800">
                                        <div class="text-center">
                                            <div class="text-3xl mb-2">Car</div>
                                            <p class="text-3xl font-black">{{ $selectedParking->price_car }}€<span
                                                    class="text-sm font-normal text-slate-500">/h</span></p>
                                            <p class="text-[10px] font-bold uppercase text-slate-500 mt-2 tracking-widest">
                                                Véhicule</p>
                                        </div>
                                        <div class="text-center">
                                            <div class="text-3xl mb-2">Moto</div>
                                            <p class="text-3xl font-black">{{ $selectedParking->price_motorcycle }}€<span
                                                    class="text-sm font-normal text-slate-500">/h</span></p>
                                            <p class="text-[10px] font-bold uppercase text-slate-500 mt-2 tracking-widest">
                                                Deux-roues</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div
                                class="h-full min-h-[450px] bg-slate-200/30 rounded-[3rem] border-2 border-dashed border-slate-200 flex flex-col items-center justify-center text-center p-12">
                                <p class="text-5xl mb-6 grayscale opacity-30">🅿️</p>
                                <h3 class="text-xl font-black text-slate-400 uppercase tracking-widest">En attente de
                                    sélection</h3>
                                <p class="text-slate-400 mt-2 text-sm max-w-xs">Choisissez un emplacement dans la liste pour
                                    consulter les données en direct.</p>
                            </div>
                        @endif
                    </main>
                </div>
            </div>
        </div>
    </div>
@endsection
