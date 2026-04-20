@extends('layouts.app')

@section('content')


    <div class="min-h-screen bg-gray-100 flex">
        <div class="w-64 min-h-screen bg-white border-r border-gray-200 flex flex-col">
            <div class="p-6 border-b border-gray-200">
                <h1 class="text-2xl font-bold text-gray-800">
                    PARK<span class="text-blue-600">SYS</span>
                </h1>
                <p class="text-xs text-gray-400 mt-1">Gestion de Parking</p>
            </div>
            <div class="p-4 border-b border-gray-200">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold text-lg">
                    </div>
                    <div>
                        <p class="font-bold text-gray-800 text-sm">{{ $user->name }}</p>
                        <p class="text-xs text-gray-400">Utilisateur</p>
                    </div>
                </div>
            </div>
            <nav class="flex flex-col p-4 gap-2">

                <p class="text-xs text-gray-400 uppercase font-bold mb-1 px-2">Menu</p>

                <a href="{{ route('user.dashboard', ['section' => 'home']) }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg font-bold text-sm
                      {{ $section === 'home' ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                    <span>Accueil</span>
                </a>

                <a href="{{ route('user.dashboard', ['section' => 'parkings']) }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg font-bold text-sm
                      {{ $section === 'parkings' ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                    <span>Parkings</span>
                </a>

                <a href="{{ route('user.dashboard', ['section' => 'tarifs']) }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg font-bold text-sm
                      {{ $section === 'tarifs' ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                    <span>Tarifs</span>
                </a>

                <a href="{{ route('user.dashboard', ['section' => 'account']) }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg font-bold text-sm
                      {{ $section === 'account' ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                    <span>Mon Profil</span>
                </a>

            </nav>
        </div>



        <div class="flex-1 p-8 overflow-y-auto">
            @if ($section === 'home')
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Accueil</h2>

                <div class="bg-white rounded-xl p-16 text-center shadow">
                    @if (session('success'))
                        <div class="bg-green-50 text-green-600 p-4 rounded-lg mb-6 border-l-4 border-green-500">
                            {{ session('success') }}
                        </div>
                    @endif
                    <h3 class="text-3xl font-bold text-gray-800 mb-4">
                        Bienvenue, {{ $user->name }}
                    </h3>

                    <p class="text-gray-500 text-lg mb-10 max-w-lg mx-auto">
                        Consultez les places disponibles en temps réel et gérez votre profil.
                    </p>

                    <div class="flex justify-center gap-4">
                        <a href="{{ route('user.dashboard', ['section' => 'parkings']) }}"
                            class="bg-gray-900 text-white px-8 py-4 rounded-lg font-bold text-lg">
                            Explorer les Parkings →
                        </a>
                        <a href="{{ route('user.agent.create') }}"
                            class=" bg-green-500 text-white px-8 py-4 rounded-lg font-bold text-lg">
                            Devenir agent →
                        </a>
                    </div>

                </div>

                <div class="grid grid-cols-3 gap-6 mt-6">
                    <div class="bg-white rounded-xl p-6 shadow text-center">
                        <div class="text-3xl mb-2">🅿️</div>
                        <p class="text-2xl font-black text-blue-600">{{ $parkings->count() }}</p>
                        <p class="text-sm text-gray-400 font-bold uppercase mt-1">Parkings disponibles</p>
                    </div>
                    <div class="bg-white rounded-xl p-6 shadow text-center">
                        <div class="text-3xl mb-2">✅</div>
                        <p class="text-2xl font-black text-green-500">{{ $availablePlaces }}</p>
                        <p class="text-sm text-gray-400 font-bold uppercase mt-1">Places libres</p>
                    </div>
                    <div class="bg-white rounded-xl p-6 shadow text-center">
                        <div class="text-3xl mb-2">🕒</div>
                        <p class="text-2xl font-black text-gray-800">24h/24</p>
                        <p class="text-sm text-gray-400 font-bold uppercase mt-1">Horaires d'accès</p>
                    </div>
                </div>
            @elseif ($section === 'parkings')
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Parkings</h2>
                <div class="grid grid-cols-4 gap-4 mb-6">
                    @foreach ($parkings as $p)
                        <a href="{{ route('user.dashboard', ['section' => 'parkings', 'parking' => $p->id]) }}"
                            class="bg-white p-4 rounded-xl text-center border-2 font-bold text-sm
                              {{ isset($selectedParking) && $selectedParking->id == $p->id
                                  ? 'border-blue-600 text-blue-600'
                                  : 'border-gray-200 text-gray-700 hover:border-blue-300' }}">
                            {{ $p->name }}
                        </a>
                    @endforeach
                </div>

                @if ($selectedParking)
                    <div class="bg-white rounded-xl shadow ">

                        <div class="bg-blue-600 p-8 text-white flex justify-between items-center">
                            <div>
                                <h3 class="text-3xl font-bold">{{ $selectedParking->name }}</h3>
                                <p class="opacity-80 text-lg mt-1">{{ $selectedParking->address }}</p>
                            </div>
                            <div class="bg-white text-blue-600 px-8 py-5 rounded-xl text-center">
                                <span class="block text-5xl font-black">{{ $availablePlaces }}</span>
                                <span class="text-sm font-bold uppercase">Places libres</span>
                            </div>
                        </div>

                        <div class="p-8 grid grid-cols-2 gap-8">

                            <div class="space-y-6">
                                <div>
                                    <p class="text-xs text-gray-400 uppercase font-bold mb-2">Téléphone</p>
                                    <p class="text-xl font-bold text-gray-800">{{ $selectedParking->phone }}</p>
                                    <p class="text-gray-500 mt-1">{{ $selectedParking->email }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 uppercase font-bold mb-2">Horaires</p>
                                    <p class="text-xl font-bold text-gray-800">🕒 {{ $selectedParking->opening_hours }}</p>
                                </div>
                            </div>

                            <div class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                                <p class="text-sm font-bold text-gray-700 mb-5 uppercase">Tarifs horaires</p>
                                <div class="space-y-4">
                                    <div
                                        class="flex justify-between items-center bg-white p-4 rounded-lg border border-gray-100">
                                        <span class="font-bold text-gray-600 text-lg">Voiture</span>
                                        <span
                                            class="text-2xl font-black text-blue-600">{{ $selectedParking->price_car }}€</span>
                                    </div>
                                    <div
                                        class="flex justify-between items-center bg-white p-4 rounded-lg border border-gray-100">
                                        <span class="font-bold text-gray-600 text-lg">Moto</span>
                                        <span
                                            class="text-2xl font-black text-blue-600">{{ $selectedParking->price_motorcycle }}€</span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                @else
                    <div class="bg-white border-2 border-dashed border-gray-300 rounded-xl p-16 text-center">
                        <p class="text-gray-400 text-xl font-bold">
                            ↑ Sélectionnez un parking ci-dessus
                        </p>
                    </div>
                @endif
            @elseif ($section === 'tarifs')
                <h2 class="text-2xl font-bold text-gray-800 mb-6">💰 Tarifs</h2>

                <div class="grid grid-cols-3 gap-6">
                    @foreach ($parkings as $p)
                        <div class="bg-white p-6 rounded-xl shadow text-center border border-gray-200">

                            <h3 class="text-xl font-bold text-gray-800 mb-5">{{ $p->name }}</h3>

                            <div class="flex gap-4">
                                <div class="flex-1 bg-gray-50 p-4 rounded-lg border border-gray-100">
                                    <p class="text-xs font-bold text-gray-400 uppercase mb-1">Voiture</p>
                                    <p class="text-2xl font-black text-blue-600">{{ $p->price_car }}€</p>
                                </div>
                                <div class="flex-1 bg-gray-50 p-4 rounded-lg border border-gray-100">
                                    <p class="text-xs font-bold text-gray-400 uppercase mb-1">Moto</p>
                                    <p class="text-2xl font-black text-blue-600">{{ $p->price_motorcycle }}€</p>
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>
            @elseif ($section === 'account')
                <h2 class="text-2xl font-bold text-gray-800 mb-6">👤 Mon Profil</h2>

                <div class="max-w-xl bg-white rounded-xl p-8 shadow">

                    <div class="space-y-4">

                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <p class="text-xs font-bold text-gray-400 uppercase mb-1">Nom</p>
                            <p class="text-lg font-bold text-gray-800">{{ $user->name }}</p>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <p class="text-xs font-bold text-gray-400 uppercase mb-1">Email</p>
                            <p class="text-lg font-bold text-gray-800">{{ $user->email }}</p>
                        </div>

                        <button
                            class="w-full bg-gray-900 text-white font-bold py-3 rounded-lg hover:bg-blue-600 transition-colors">
                            Modifier mon profil
                        </button>

                    </div>

                </div>
            @endif

        </div>

    </div>

@endsection
