@extends('layouts.app')

@section('content')
    <div class="min-h-screen w-full flex flex-col lg:flex-row font-sans antialiased bg-slate-50">

        {{-- BARRE LATERALE FIXE (GAUCHE - 40%) --}}
        <div class="relative w-full lg:w-[40%] h-80 lg:h-screen lg:sticky lg:top-0 bg-slate-900 overflow-hidden">
            @php
                // Image dynamique selon la section
                $bgImages = [
                    'statistics' => asset('images/statistique.webp'),
                    'users' => 'https://images.unsplash.com/photo-1521737711867-e3b97375f902?q=80&w=2070',
                    'parkings' => 'https://images.unsplash.com/photo-1506521781263-d8422e82f27a?q=80&w=2070',
                    'demandes'   => asset('images/agent.png'),
                ];
                $currentImage = $bgImages[$section] ?? $bgImages['statistics'];
            @endphp

            <img src="{{ $currentImage }}" alt="Admin Context" class="absolute inset-0 w-full h-full object-cover opacity-40">
            <div class="absolute inset-0 flex flex-col justify-end p-10 md:p-16 text-white">
                <span class="w-12 h-1.5 bg-blue-500 mb-6 rounded-full"></span>
                <h2 class="text-5xl font-black tracking-tighter uppercase leading-[0.9] mb-4">
                    Administration <br><span class="text-blue-400 italic">{{ ucfirst($section) }}</span>
                </h2>
                <p class="text-slate-300 text-lg font-medium max-w-xs leading-relaxed">
                    Gérez l'infrastructure ParkSys, surveillez les flux et optimisez la rentabilité de vos sites.
                </p>
            </div>
        </div>

        {{-- ZONE DE CONTENU (DROITE - 60%) --}}
        <div class="w-full lg:w-[60%] flex flex-col p-6 md:p-12 lg:p-20 overflow-y-auto">
            <div class="max-w-4xl w-full mx-auto">

                {{-- HEADER LOGO & RETOUR --}}
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-12 gap-6">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center text-white text-xl font-black shadow-lg shadow-blue-200">
                            P</div>
                        <div>
                            <h1 class="text-2xl font-black text-slate-900 tracking-tight">ParkSys</h1>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Espace Administrateur
                            </p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('admin.dashboard', ['section' => 'statistics']) }}"
                            class="p-3 rounded-xl {{ $section == 'statistics' ? 'bg-blue-600 text-white' : 'bg-white text-slate-400 border border-slate-200' }} hover:scale-105 transition-all">📊</a>
                        <a href="{{ route('admin.dashboard', ['section' => 'parkings']) }}"
                            class="p-3 rounded-xl {{ $section == 'parkings' ? 'bg-blue-600 text-white' : 'bg-white text-slate-400 border border-slate-200' }} hover:scale-105 transition-all">🅿️</a>
                        <a href="{{ route('admin.dashboard', ['section' => 'users']) }}"
                            class="p-3 rounded-xl {{ $section == 'users' ? 'bg-blue-600 text-white' : 'bg-white text-slate-400 border border-slate-200' }} hover:scale-105 transition-all">👥</a>
                        <a href="{{ route('admin.dashboard', ['section' => 'demandes']) }}"
                            class="p-3 rounded-xl {{ $section == 'demandes' ? 'bg-blue-600 text-white' : 'bg-white text-slate-400 border border-slate-200' }} hover:scale-105 transition-all relative">
                            📩 @if ($pendingRequests > 0)
                                <span
                                    class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full text-[10px] flex items-center justify-center text-white">{{ $pendingRequests }}</span>
                            @endif
                        </a>
                    </div>
                </div>

                {{-- MESSAGES FLASH --}}
                @if (session('success'))
                    <div
                        class="bg-green-50 text-green-700 p-4 rounded-2xl border border-green-100 mb-8 flex items-center gap-3 animate-fade-in">
                        <span class="text-xl">✅</span> {{ session('success') }}
                    </div>
                @endif

                {{-- SECTION : STATISTICS --}}
                @if ($section == 'statistics')
                    <div class="space-y-8">
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach ([['label' => 'Membres', 'val' => $totalUsers, 'color' => 'slate'], ['label' => 'Agents', 'val' => $totalAgents, 'color' => 'blue'], ['label' => 'Total Places', 'val' => $totalPlaces, 'color' => 'slate'], ['label' => 'En attente', 'val' => $pendingRequests, 'color' => 'orange'], ['label' => 'Véhicules', 'val' => $vehiclesInside, 'color' => 'blue'], ['label' => 'Libres', 'val' => $freePlaces, 'color' => 'green']] as $stat)
                                <div class="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">
                                        {{ $stat['label'] }}</p>
                                    <p class="text-3xl font-black text-{{ $stat['color'] }}-600">{{ $stat['val'] }}</p>
                                </div>
                            @endforeach
                        </div>

                        <div class="bg-slate-900 rounded-[2.5rem] p-10 text-white relative overflow-hidden">
                            <div class="relative z-10">
                                <p class="text-[10px] font-black uppercase opacity-60 mb-2">Taux d'occupation global</p>
                                <div class="flex items-end gap-4">
                                    <p class="text-7xl font-black tracking-tighter">{{ $occupation }}%</p>
                                    <div class="mb-3 w-full bg-slate-800 rounded-full h-4 overflow-hidden">
                                        <div class="bg-blue-500 h-full rounded-full" style="width: {{ $occupation }}%">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div
                                class="bg-white p-8 rounded-[2rem] border border-slate-200 flex justify-between items-center">
                                <div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase">Entrées Jour</p>
                                    <p class="text-4xl font-black text-slate-900">{{ $entriesToday }}</p>
                                </div>
                                <span class="text-4xl">⬇️</span>
                            </div>
                            <div
                                class="bg-white p-8 rounded-[2rem] border border-slate-200 flex justify-between items-center">
                                <div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase">Sorties Jour</p>
                                    <p class="text-4xl font-black text-slate-900">{{ $exitsToday }}</p>
                                </div>
                                <span class="text-4xl">⬆️</span>
                            </div>
                        </div>
                    </div>
                @endif
                {{-- SECTION : USERS --}}
                @if ($section == 'users')
                    <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-sm overflow-hidden">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-slate-50/50 border-b border-slate-100">
                                <tr>
                                    <th class="p-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                        Utilisateur</th>
                                    <th class="p-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Rôle
                                    </th>
                                    <th
                                        class="p-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach ($users as $user)
                                    <tr class="group hover:bg-slate-50/50 transition-all">
                                        <td class="p-6">
                                            <p class="font-bold text-slate-900">{{ $user->name }}</p>
                                            <p class="text-xs text-slate-400 font-medium">{{ $user->email }}</p>
                                        </td>
                                        <td class="p-6">
                                            <span
                                                class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter {{ $user->role->name == 'agent' ? 'bg-blue-100 text-blue-600' : 'bg-slate-100 text-slate-500' }}">
                                                {{ $user->role->name }}
                                            </span>
                                        </td>
                                        <td class="p-6 text-center">
                                            @if ($user->role->name == 'agent')
                                                <form method="POST" action="{{ route('admin.delete', $user->id) }}"
                                                    onsubmit="return confirm('Supprimer cet agent ?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                        class="w-10 h-10 inline-flex items-center justify-center rounded-xl bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-all">🗑</button>
                                                </form>
                                            @else
                                                <span
                                                    class="text-[10px] font-bold text-slate-300 uppercase italic">Protégé</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                {{-- SECTION : PARKINGS --}}
                @if ($section == 'parkings')
                    <div class="space-y-6">
                        <div class="flex justify-between items-end mb-4">
                            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">Gestion des Sites
                            </h3>
                            <button onclick="document.getElementById('addModal').classList.remove('hidden')"
                                class="bg-blue-600 text-white px-6 py-3 rounded-2xl font-bold text-xs uppercase tracking-widest shadow-lg shadow-blue-200 hover:bg-slate-900 transition-all">
                                + Ajouter un parking
                            </button>
                        </div>

                        <div class="grid grid-cols-1 gap-4">
                            @foreach ($parkings as $parking)
                                <div
                                    class="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm flex flex-col md:flex-row justify-between items-center gap-6">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-1">
                                            <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                            <h4 class="font-black text-xl text-slate-900">{{ $parking->name }}</h4>
                                        </div>
                                        <p class="text-xs text-slate-400 font-medium uppercase tracking-wider">
                                            {{ $parking->address }}</p>
                                        <div class="flex gap-4 mt-4 text-[10px] font-bold text-slate-500">
                                            <span>🕒 {{ $parking->opening_hours }}</span>
                                            <span class="text-blue-600 font-black">🚗 {{ $parking->total_places }}
                                                PLACES</span>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <button
                                            onclick="document.getElementById('editModal-{{ $parking->id }}').classList.remove('hidden')"
                                            class="w-12 h-12 flex items-center justify-center rounded-2xl bg-slate-50 text-slate-600 hover:bg-blue-600 hover:text-white transition-all">✏️</button>
                                        <form method="POST" action="{{ route('parkings.delete', $parking->id) }}"
                                            onsubmit="return confirm('Supprimer ce parking ?')">
                                            @csrf @method('DELETE')
                                            <button
                                                class="w-12 h-12 flex items-center justify-center rounded-2xl bg-red-50 text-red-500 hover:bg-red-600 hover:text-white transition-all">🗑</button>
                                        </form>
                                    </div>
                                    <div id="editModal-{{ $parking->id }}"
                                        class="hidden fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">

                                        <div
                                            class="bg-white w-full max-w-md rounded-[2.5rem] p-10 shadow-2xl animate-modal-up">
                                            <h3 class="text-3xl font-black text-slate-900 tracking-tighter mb-6">Modifier
                                                Parking</h3>

                                            <form method="POST" action="{{ route('parkings.update', $parking->id) }}"
                                                class="space-y-4">
                                                @csrf
                                                @method('PUT')

                                                <input name="name" placeholder="Nom du parking"
                                                    class="w-full bg-slate-50 border-none rounded-2xl p-4 text-sm font-medium focus:ring-2 focus:ring-blue-500"
                                                    required value="{{ $parking->name }}">

                                                <input name="address" placeholder="Adresse complète"
                                                    class="w-full bg-slate-50 border-none rounded-2xl p-4 text-sm font-medium focus:ring-2 focus:ring-blue-500"
                                                    required value="{{ $parking->address }}">

                                                <div class="grid grid-cols-2 gap-4">
                                                    <input name="opening_hours" placeholder="Horaires"
                                                        class="bg-slate-50 border-none rounded-2xl p-4 text-sm font-medium focus:ring-2 focus:ring-blue-500"
                                                        required value="{{ $parking->opening_hours }}">

                                                    <input name="phone" placeholder="Téléphone"
                                                        class="bg-slate-50 border-none rounded-2xl p-4 text-sm font-medium focus:ring-2 focus:ring-blue-500"
                                                        required value="{{ $parking->phone }}">
                                                </div>

                                                <input name="email" type="email" placeholder="Email de contact"
                                                    class="w-full bg-slate-50 border-none rounded-2xl p-4 text-sm font-medium focus:ring-2 focus:ring-blue-500"
                                                    required value="{{ $parking->email }}">

                                                <div class="grid grid-cols-2 gap-4">
                                                    <div class="space-y-1">
                                                        <p class="text-[9px] font-black text-slate-400 uppercase ml-2">Prix
                                                            Voiture / h</p>
                                                        <input type="number" step="0.01" name="price_car"
                                                            class="w-full bg-slate-50 border-none rounded-2xl p-4 text-sm font-medium focus:ring-2 focus:ring-blue-500"
                                                            required value="{{ $parking->price_car }}">
                                                    </div>

                                                    <div class="space-y-1">
                                                        <p class="text-[9px] font-black text-slate-400 uppercase ml-2">Prix
                                                            Moto / h</p>
                                                        <input type="number" step="0.01" name="price_motorcycle"
                                                            class="w-full bg-slate-50 border-none rounded-2xl p-4 text-sm font-medium focus:ring-2 focus:ring-blue-500"
                                                            required value="{{ $parking->price_motorcycle }}">
                                                    </div>
                                                </div>

                                                <div class="flex gap-3 mt-6">
                                                    <button type="button"
                                                        onclick="document.getElementById('editModal-{{ $parking->id }}').classList.add('hidden')"
                                                        class="flex-1 py-4 text-xs font-black uppercase text-slate-400 hover:text-slate-900 transition-colors">
                                                        Annuler
                                                    </button>

                                                    <button
                                                        class="flex-[2] bg-blue-600 text-white py-4 rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-blue-100 hover:bg-blue-700 transition-all active:scale-95">
                                                        Mettre à jour
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- SECTION : DEMANDES --}}
                @if ($section == 'demandes')
                    <div class="grid grid-cols-1 gap-6">
                        @forelse ($agentRequests as $req)
                            <div
                                class="bg-white rounded-[2.5rem] border border-slate-200 p-8 shadow-sm relative overflow-hidden group">
                                <div class="flex justify-between items-start mb-6">
                                    <div>
                                        <h4 class="text-2xl font-black text-slate-900 tracking-tighter">
                                            {{ $req->user->name }}</h4>
                                        <p class="text-sm text-slate-400 font-medium">{{ $req->user->email }}</p>
                                    </div>
                                    <span
                                        class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest {{ $req->status == 'pending' ? 'bg-orange-100 text-orange-600' : 'bg-green-100 text-green-600' }}">
                                        {{ $req->status }}
                                    </span>
                                </div>

                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                                    <div class="bg-slate-50 p-4 rounded-2xl">
                                        <p class="text-[9px] font-black text-slate-400 uppercase mb-1">Expérience</p>
                                        <p class="font-bold text-slate-900">{{ $req->experience }} ans</p>
                                    </div>
                                    <div class="bg-slate-50 p-4 rounded-2xl">
                                        <p class="text-[9px] font-black text-slate-400 uppercase mb-1">Âge</p>
                                        <p class="font-bold text-slate-900">{{ $req->age }} ans</p>
                                    </div>
                                    <div class="bg-slate-50 p-4 rounded-2xl">
                                        <p class="text-[9px] font-black text-slate-400 uppercase mb-1">Téléphone</p>
                                        <p class="font-bold text-slate-900">{{ $req->phone }}</p>
                                    </div>
                                    <div class="bg-slate-50 p-4 rounded-2xl">
                                        <p class="text-[9px] font-black text-slate-400 uppercase mb-1">Dispo</p>
                                        <p class="font-bold text-slate-900">{{ $req->availability }}</p>
                                    </div>
                                </div>

                                <div class="flex flex-col md:flex-row md:items-center gap-4 pt-6 border-t border-slate-50">
                                    <div class="flex gap-2">
                                        <a href="{{ asset('storage/' . $req->cv_document) }}" target="_blank"
                                            class="flex items-center gap-2 px-4 py-3 bg-white border border-slate-200 rounded-xl text-[10px] font-black text-slate-600 uppercase hover:bg-slate-50 hover:border-blue-300 transition-all shadow-sm whitespace-nowrap">
                                            📄 CV
                                        </a>
                                        <a href="{{ asset('storage/' . $req->identity_document) }}" target="_blank"
                                            class="flex items-center gap-2 px-4 py-3 bg-white border border-slate-200 rounded-xl text-[10px] font-black text-slate-600 uppercase hover:bg-slate-50 hover:border-indigo-300 transition-all shadow-sm whitespace-nowrap">
                                            🪪 CARTE ID
                                        </a>
                                    </div>

                                    <div class="hidden md:block w-px h-8 bg-slate-100 mx-2"></div>

                                    <div class="flex flex-1 gap-3">
                                        <form method="POST" action="{{ route('admin.agent.accept', $req->id) }}"
                                            class="flex-1">
                                            @csrf
                                            <button
                                                class="w-full bg-emerald-500 text-white py-3 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-100 active:scale-95 whitespace-nowrap">
                                                Accepter l'agent
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.agent.reject', $req->id) }}">
                                            @csrf
                                            <button
                                                class="px-6 bg-slate-100 text-slate-500 py-3 rounded-xl font-black text-[10px] uppercase hover:bg-red-50 hover:text-red-500 transition-all active:scale-95 whitespace-nowrap">
                                                Refuser
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-20 bg-white rounded-[3rem] border border-dashed border-slate-200">
                                <p class="text-slate-400 font-bold uppercase tracking-widest text-xs">Aucune demande en
                                    attente</p>
                            </div>
                        @endforelse
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- MODAL AJOUT (IDEM POUR EDIT) --}}
    <div id="addModal"
        class="hidden fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-md rounded-[2.5rem] p-10 shadow-2xl animate-modal-up">
            <h3 class="text-3xl font-black text-slate-900 tracking-tighter mb-6">Nouveau Site</h3>
            <form method="POST" action="{{ route('parkings.store') }}" class="space-y-4">
                @csrf
                <input name="name" placeholder="Nom du parking"
                    class="w-full bg-slate-50 border-none rounded-2xl p-4 text-sm font-medium focus:ring-2 focus:ring-blue-500"
                    required>
                <input name="address" placeholder="Adresse complète"
                    class="w-full bg-slate-50 border-none rounded-2xl p-4 text-sm font-medium focus:ring-2 focus:ring-blue-500"
                    required>
                <div class="grid grid-cols-2 gap-4">
                    <input type="number" name="total_places" placeholder="Total Places"
                        class="bg-slate-50 border-none rounded-2xl p-4 text-sm font-medium focus:ring-2 focus:ring-blue-500"
                        required>
                    <input name="opening_hours" placeholder="Horaires"
                        class="bg-slate-50 border-none rounded-2xl p-4 text-sm font-medium focus:ring-2 focus:ring-blue-500"
                        required>
                </div>
                <input name="email" placeholder="email"
                    class="w-full bg-slate-50 border-none rounded-2xl p-4 text-sm font-medium focus:ring-2 focus:ring-blue-500"
                    required>
                <input name="phone" placeholder="phone"
                    class="w-full bg-slate-50 border-none rounded-2xl p-4 text-sm font-medium focus:ring-2 focus:ring-blue-500"
                    required>
                <div class="grid grid-cols-2 gap-4">
                    <input name="price_car" placeholder="price_car"
                        class="w-full bg-slate-50 border-none rounded-2xl p-4 text-sm font-medium focus:ring-2 focus:ring-blue-500"
                        required>
                    <input name="price_motorcycle" placeholder="price_motorcycle"
                        class="w-full bg-slate-50 border-none rounded-2xl p-4 text-sm font-medium focus:ring-2 focus:ring-blue-500"
                        required>
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')"
                        class="flex-1 py-4 text-xs font-black uppercase text-slate-400 hover:text-slate-900 transition-colors">Annuler</button>
                    <button
                        class="flex-[2] bg-blue-600 text-white py-4 rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-blue-100">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    {{--  --}}
@endsection
