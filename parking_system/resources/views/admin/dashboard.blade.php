@extends('layouts.app')

@section('content')
    <div class="min-h-screen w-full flex flex-col lg:flex-row font-sans antialiased bg-slate-50">

        {{-- ZONE DE CONTENU (GAUCHE - 60%) --}}
        <div class="w-full lg:w-[60%] flex flex-col p-6 md:p-12 lg:p-20 overflow-y-auto order-2 lg:order-1">
            <div class="max-w-4xl w-full mx-auto">

                {{-- HEADER LOGO & NAVIGATION --}}
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-12 gap-6">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center text-white text-xl font-black shadow-lg shadow-blue-200">
                            P
                        </div>
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
                        <a href="{{ route('admin.dashboard', ['section' => 'history']) }}"
                            class="p-3 rounded-xl {{ $section == 'history' ? 'bg-blue-600 text-white' : 'bg-white border' }}">📜</a>
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

                        {{-- STATS --}}
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

                        {{-- OCCUPATION --}}
                        <div class="bg-slate-900 rounded-3xl p-8 text-white">
                            <p class="text-xs uppercase opacity-60">Taux d'occupation</p>

                            <div class="flex items-end gap-4">
                                <h2 class="text-6xl font-black">{{ $occupation }}%</h2>

                                <div class="w-full bg-slate-800 h-4 rounded-full overflow-hidden">
                                    <div class="bg-blue-500 h-full" style="width: {{ $occupation }}%"></div>
                                </div>
                            </div>
                        </div>

                        {{-- ACTIVITÉ --}}
                        <div class="bg-white p-6 rounded-2xl border">
                            <h3 class="font-black mb-4">Activité</h3>

                            <div class="grid grid-cols-3 text-center">

                                <div>
                                    <p class="text-xs text-slate-400">Dernière entrée</p>
                                    <p class="font-bold">
                                        {{ $lastEntry->entry_time ?? '---' }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-xs text-slate-400">Dernière sortie</p>
                                    <p class="font-bold">
                                        {{ $lastExit->exit_time ?? '---' }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-xs text-slate-400">Aujourd’hui</p>
                                    <p class="font-bold">{{ date('Y-m-d') }}</p>
                                </div>

                            </div>
                        </div>

                        {{-- REVENU --}}
                        <div class="bg-white p-6 rounded-2xl border">
                            <p class="text-xs text-slate-400">Revenu aujourd’hui</p>
                            <h2 class="text-4xl font-black text-green-600">
                                {{ $todayRevenue }} DH
                            </h2>
                        </div>

                        {{-- DERNIÈRES ENTRÉES --}}
                        <div class="bg-white rounded-2xl border overflow-hidden">
                            <h3 class="p-6 font-black">Dernières entrées</h3>

                            <table class="w-full">
                                <tr class="bg-slate-100 text-sm">
                                    <th class="p-4">Plaque</th>
                                    <th>Parking</th>
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
                            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">Gestion des
                                Sites
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
                                </div>
                                {{-- Modal Edit spécifique --}}
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

                                            <input name="name"
                                                class="w-full bg-slate-50 border-none rounded-2xl p-4 text-sm font-medium focus:ring-2 focus:ring-blue-500"
                                                required value="{{ $parking->name }}" placeholder="Nom du parking">

                                            <input name="address"
                                                class="w-full bg-slate-50 border-none rounded-2xl p-4 text-sm font-medium focus:ring-2 focus:ring-blue-500"
                                                required value="{{ $parking->address }}" placeholder="Adresse complète">

                                            <div class="grid grid-cols-2 gap-4 ">
                                                <input type="tel" name="phone"
                                                    class="bg-slate-50 border-none rounded-2xl p-4 text-sm font-medium focus:ring-2 focus:ring-blue-500"
                                                    required value="{{ $parking->phone }}" placeholder="Téléphone">
                                                <input type="email" name="email" class=" cursor-not-allowed" readonly
                                                    class="bg-slate-50 border-none rounded-2xl p-4 text-sm font-medium focus:ring-2 focus:ring-blue-500"
                                                    value="{{ $parking->email }}" placeholder="Email">
                                            </div>

                                            <div class="grid grid-cols-2 gap-4">
                                                <input type="number" name="total_places"
                                                    class="bg-slate-50 border-none rounded-2xl p-4 text-sm font-medium focus:ring-2 focus:ring-blue-500"
                                                    required value="{{ $parking->total_places }}"
                                                    placeholder="Total Places">
                                                <input name="opening_hours"
                                                    class="bg-slate-50 border-none rounded-2xl p-4 text-sm font-medium focus:ring-2 focus:ring-blue-500"
                                                    required value="{{ $parking->opening_hours }}"
                                                    placeholder="Horaires">
                                            </div>

                                            <div class="grid grid-cols-2 gap-4">
                                                <div class="relative">
                                                    <input type="number" step="0.01" name="price_car"
                                                        class="w-full bg-slate-50 border-none rounded-2xl p-4 text-sm font-medium focus:ring-2 focus:ring-blue-500"
                                                        required value="{{ $parking->price }}"
                                                        placeholder="Tarif Voiture">
                                                    <span
                                                        class="absolute right-4 top-4 text-slate-400 text-[10px] font-bold">/H</span>
                                                </div>

                                            </div>

                                            <div class="flex gap-3 mt-6">
                                                <button type="button"
                                                    onclick="document.getElementById('editModal-{{ $parking->id }}').classList.add('hidden')"
                                                    class="flex-1 py-4 text-xs font-black uppercase text-slate-400 hover:text-slate-600 transition-colors">
                                                    Annuler
                                                </button>
                                                <button type="submit"
                                                    class="flex-[2] bg-blue-600 text-white py-4 rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-blue-100 transition-all active:scale-95">
                                                    Mettre à jour
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
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
                {{-- SECTION : DEMANDES --}}
                @if ($section == 'demandes')
                    <div class="grid grid-cols-1 gap-6">

                        @forelse ($agentRequests as $req)
                            {{-- CARD --}}
                            <div class="bg-white rounded-[2.5rem] border border-slate-200 p-8 shadow-sm">

                                <div class="flex justify-between items-start mb-6">

                                    <div>
                                        <h4 class="text-2xl font-black text-slate-900">
                                            {{ $req->user->name }}
                                        </h4>

                                        <p class="text-sm text-slate-400">
                                            {{ $req->user->email }}
                                        </p>
                                    </div>

                                    <span
                                        class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase
                    {{ $req->status == 'pending' ? 'bg-orange-100 text-orange-600' : 'bg-green-100 text-green-600' }}">
                                        {{ $req->status }}
                                    </span>

                                </div>

                                {{-- ACTIONS --}}
                                <div class="flex gap-3 pt-6 border-t">

                                    {{-- VOIR DETAILS --}}
                                    <button
                                        onclick="document.getElementById('modal-{{ $req->id }}').classList.remove('hidden')"
                                        class="flex-1 bg-blue-50 text-blue-600 py-3 rounded-xl font-bold text-xs hover:bg-blue-600 hover:text-white transition">
                                        Voir détails
                                    </button>

                                </div>

                            </div>

                            {{-- ================= MODAL ================= --}}
                            <div id="modal-{{ $req->id }}"
                                class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4">

                                <div class="bg-white w-full max-w-2xl rounded-[2rem] shadow-2xl overflow-hidden">

                                    {{-- HEADER --}}
                                    <div class="flex justify-between items-center p-6 border-b">
                                        <div>
                                            <h3 class="text-2xl font-black text-slate-900">Détails Agent</h3>
                                            <p class="text-xs text-slate-400">Demande reçue</p>
                                        </div>

                                        <button onclick="closeModal({{ $req->id }})"
                                            class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-100 hover:bg-red-100 text-slate-500 hover:text-red-500 transition">
                                            ✕
                                        </button>
                                    </div>

                                    {{-- CONTENT --}}
                                    <div class="p-6 space-y-6">

                                        {{-- INFOS --}}
                                        <div class="grid grid-cols-2 gap-4 text-sm">

                                            <div class="bg-slate-50 p-4 rounded-xl">
                                                <p class="text-xs text-slate-400">👤 Nom</p>
                                                <p class="font-bold">{{ $req->user->name }}</p>
                                            </div>

                                            <div class="bg-slate-50 p-4 rounded-xl">
                                                <p class="text-xs text-slate-400">📧 Email</p>
                                                <p class="font-bold">{{ $req->user->email }}</p>
                                            </div>

                                            <div class="bg-slate-50 p-4 rounded-xl">
                                                <p class="text-xs text-slate-400">📱 Téléphone</p>
                                                <p class="font-bold">{{ $req->phone ?? '---' }}</p>
                                            </div>

                                            <div class="bg-slate-50 p-4 rounded-xl">
                                                <p class="text-xs text-slate-400">📅 Date</p>
                                                <p class="font-bold">{{ $req->created_at->format('d/m/Y H:i') }}</p>
                                            </div>

                                        </div>

                                        {{-- MESSAGE --}}
                                        <div>
                                            <p class="text-xs text-slate-400 mb-2">💬 Motivation</p>
                                            <div class="bg-slate-100 p-4 rounded-xl text-sm leading-relaxed">
                                                {{ $req->motivation ?? 'Aucun message' }}
                                            </div>
                                        </div>

                                        {{-- DOCUMENT --}}
                                        <div class="bg-slate-50 p-4 rounded-xl flex items-center justify-between">

                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-10 h-10 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center">
                                                    📄
                                                </div>
                                                <div>
                                                    <p class="font-bold text-sm">CV</p>
                                                    <p class="text-xs text-slate-400">Document candidat</p>
                                                </div>
                                            </div>

                                            @if ($req->cv_document)
                                                <a href="{{ asset('storage/' . $req->cv_document) }}" target="_blank"
                                                    class="text-blue-600 font-bold text-sm hover:underline">
                                                    Voir →
                                                </a>
                                            @else
                                                <span class="text-slate-300 text-sm">Non fourni</span>
                                            @endif

                                        </div>

                                    </div>

                                    {{-- ACTIONS --}}
                                    <div class="border-t p-6 flex justify-between items-center">

                                        {{-- CLOSE --}}
                                        <button onclick="closeModal({{ $req->id }})"
                                            class="flex items-center gap-2 text-slate-400 hover:text-red-500 font-bold text-sm transition">
                                            ← Fermer
                                        </button>

                                        <div class="flex gap-3">

                                            {{-- REFUSER --}}
                                            <form method="POST" action="{{ route('admin.agent.reject', $req->id) }}">
                                                @csrf
                                                <button
                                                    class="flex items-center gap-2 px-5 py-2 rounded-xl bg-red-50 text-red-600 font-bold hover:bg-red-100 transition">
                                                    ❌ Refuser
                                                </button>
                                            </form>

                                            {{-- ACCEPTER --}}
                                            <form method="POST" action="{{ route('admin.agent.accept', $req->id) }}">
                                                @csrf
                                                <button
                                                    class="flex items-center gap-2 px-5 py-2 rounded-xl bg-green-50 text-green-600 font-bold hover:bg-green-100 transition">
                                                    ✅ Accepter
                                                </button>
                                            </form>

                                        </div>

                                    </div>

                                </div>
                            </div>
                            {{-- ================= END MODAL ================= --}}

                        @empty
                            <div class="text-center py-20 bg-white rounded-[3rem] border border-dashed">
                                <p class="text-slate-400 font-bold uppercase text-xs">
                                    Aucune demande en attente
                                </p>
                            </div>
                        @endforelse

                    </div>
                @endif
            </div>
        </div>

        {{-- BARRE LATERALE IMAGE (DROITE - 40%) --}}
        <div
            class="relative w-full lg:w-[40%] h-80 lg:h-screen lg:sticky lg:top-0 bg-slate-900 overflow-hidden order-1 lg:order-2">
            @php
                $bgImages = [
                    'parkings' => 'https://images.unsplash.com/photo-1506521781263-d8422e82f27a?q=80&w=2070',
                ];
            @endphp

            <img src="{{ $bgImages['parkings'] }}" alt="Admin Context"
                class="absolute inset-0 w-full h-full object-cover opacity-40">
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
    </div>

    {{-- MODAL AJOUT --}}
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
                    <input type="tel" name="phone" placeholder="Téléphone"
                        class="bg-slate-50 border-none rounded-2xl p-4 text-sm font-medium focus:ring-2 focus:ring-blue-500">
                    <input type="email" name="email" placeholder="Email du site"
                        class="bg-slate-50 border-none rounded-2xl p-4 text-sm font-medium focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <input type="number" name="total_places" placeholder="Total Places"
                        class="bg-slate-50 border-none rounded-2xl p-4 text-sm font-medium focus:ring-2 focus:ring-blue-500"
                        required>
                    <input name="opening_hours" placeholder="Horaires"
                        class="bg-slate-50 border-none rounded-2xl p-4 text-sm font-medium focus:ring-2 focus:ring-blue-500"
                        required>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="relative">
                        <input type="number" step="0.01" name="price" placeholder="Tarif Voiture"
                            class="w-full bg-slate-50 border-none rounded-2xl p-4 text-sm font-medium focus:ring-2 focus:ring-blue-500"
                            required>
                        <span class="absolute right-4 top-4 text-slate-400 text-[10px] font-bold">/H</span>
                    </div>

                </div>

                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')"
                        class="flex-1 py-4 text-xs font-black uppercase text-slate-400">Annuler</button>
                    <button
                        class="flex-[2] bg-blue-600 text-white py-4 rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-blue-100 transition-transform active:scale-95">
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
