@extends('layouts.app')

@section('content')
    {{-- Conteneur principal --}}
    <div class="min-h-screen bg-slate-50 flex items-center justify-center p-4 md:p-8">

        {{-- CADRE PRINCIPAL --}}
        <div
            class="w-full max-w-7xl bg-white rounded-3xl shadow-2xl overflow-hidden flex h-[750px] border border-slate-100 relative">

            {{-- 1. COLONNE GAUCHE : PHOTO --}}
            <div class="relative w-1/2 h-full bg-slate-900 overflow-hidden hidden lg:block">
                <img src="https://images.unsplash.com/photo-1556155092-490a1ba16284?q=80&w=2070"
                    class="absolute inset-0 w-full h-full object-cover opacity-40">

                <div class="relative z-10 h-full flex flex-col justify-between p-16 text-white">
                    <div>
                        <div class="mb-16">
                            <h2 class="text-4xl font-black tracking-tighter italic">PARK<span class="text-blue-500">SYS</span>
                            </h2>
                            <p class="text-slate-400 text-sm font-bold uppercase tracking-widest mt-1">Recrutement</p>
                        </div>
                        <h3 class="text-5xl font-extrabold leading-tight tracking-tight mb-6">Devenez <span
                                class="text-blue-400 italic">Agent</span>.</h3>
                        <p class="text-slate-300 text-lg max-w-md leading-relaxed">Rejoignez-nous pour gérer nos parkings et
                            moderniser le stationnement urbain.</p>
                    </div>
                    <div class="grid grid-cols-2 gap-6 border-t border-slate-700 pt-10 font-bold">
                        <p>💼 Emploi Stable</p>
                        <p>📈 Évolution</p>
                    </div>
                </div>
            </div>

            {{-- 2. COLONNE DROITE : FORMULAIRE --}}
            <div class="flex-1 lg:w-1/2 p-8 md:p-16 flex flex-col relative bg-slate-50/50">

                {{-- BOUTON QUITTER (En haut à droite) --}}
                <div class="absolute top-8 right-8">
                    <a href="{{ route('user.dashboard') }}"
                        class="flex items-center gap-2 text-slate-400 hover:text-red-500 font-bold text-xs uppercase tracking-widest transition-colors group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:rotate-90 transition-transform"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Quitter
                    </a>
                </div>

                <div class="mt-4">
                    <h2 class="text-3xl font-bold text-slate-800 mb-8 tracking-tight italic">Postuler</h2>

                    {{-- INDICATEUR D'ÉTAPES --}}
                    <div
                        class="flex items-center justify-between mb-10 bg-white p-3 rounded-xl border border-slate-100 shadow-sm">
                        @php $steps_labels = [1 => 'Infos', 2 => 'Exp', 3 => 'Docs', 4 => 'Fin']; @endphp
                        @foreach ([1, 2, 3, 4] as $i)
                            <div class="flex items-center gap-2">
                                <span
                                    class="h-8 w-8 rounded-lg flex items-center justify-center font-bold text-sm
                                    {{ $step == $i ? 'bg-blue-600 text-white shadow-lg shadow-blue-100' : ($step > $i ? 'bg-green-500 text-white' : 'bg-slate-200 text-slate-500') }}">
                                    {{ $i }}
                                </span>
                                <span
                                    class="text-xs font-bold uppercase {{ $step == $i ? 'text-blue-600' : 'text-slate-400' }} hidden md:block">
                                    {{ $steps_labels[$i] }}
                                </span>
                            </div>
                            @if ($i < 4)
                                <div class="flex-1 h-px bg-slate-200 mx-2"></div>
                            @endif
                        @endforeach
                    </div>

                    {{-- ÉTAPE 1 --}}
                    @if ($step == 1)
                        <form method="POST" action="{{ route('user.agent.step') }}" class="space-y-6">
                            @csrf
                            <input type="hidden" name="step" value="1">
                            <div class="space-y-4">
                                <input type="text" value="{{ auth()->user()->name }}"
                                    class="w-full bg-slate-100 border p-4 rounded-xl text-slate-500 cursor-not-allowed italic"
                                    readonly>
                                <input type="email" value="{{ auth()->user()->email }}"
                                    class="w-full bg-slate-100 border p-4 rounded-xl text-slate-500 cursor-not-allowed italic"
                                    readonly>
                                <input type="text" name="phone" placeholder="Téléphone *"
                                    class="w-full border p-4 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none"
                                    required>
                                <input type="number" name="age" placeholder="Âge *"
                                    class="w-full border p-4 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none"
                                    required>
                            </div>
                            <div class="pt-8 text-right">
                                <button
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-12 rounded-xl shadow-xl transition-all">Continuer
                                    →</button>
                            </div>
                        </form>

                        {{-- ÉTAPE 2 --}}
                    @elseif($step == 2)
                        <form method="POST" action="{{ route('user.agent.step') }}" class="space-y-6">
                            @csrf
                            <input type="hidden" name="step" value="2">
                            <select name="experience" class="w-full border p-4 rounded-xl bg-white outline-none" required>
                                <option value="">Choisir expérience</option>
                                <option>Débutant</option>
                                <option>Intermédiaire</option>
                                <option>Expert</option>
                            </select>
                            <select name="availability" class="w-full border p-4 rounded-xl bg-white outline-none" required>
                                <option value="">Choisir disponibilité</option>
                                <option>Temps plein</option>
                                <option>Matin</option>
                                <option>Après-midi</option>
                            </select>
                            <textarea name="motivation" placeholder="Motivation..." class="w-full border p-4 rounded-xl h-40 outline-none"></textarea>
                            <div class="pt-6 flex justify-between items-center">
                                <a href="{{ route('user.agent.create', ['step' => 1]) }}"
                                    class="text-slate-400 font-bold underline">← Retour</a>
                                <button class="bg-blue-600 text-white font-bold py-4 px-12 rounded-xl shadow-xl">Continuer
                                    →</button>
                            </div>
                        </form>

                        {{-- ÉTAPE 3 --}}
                    @elseif($step == 3)
                        <form method="POST" action="{{ route('user.agent.step') }}" enctype="multipart/form-data"
                            class="space-y-6">
                            @csrf
                            <input type="hidden" name="step" value="3">
                            <div class="p-6 bg-white rounded-2xl border border-slate-200">
                                <p class="font-bold text-slate-800 uppercase text-xs mb-2">Carte d'identité *</p>
                                <input type="file" name="identity_document" class="w-full text-sm cursor-pointer"
                                    required>
                            </div>
                            <div class="p-6 bg-white rounded-2xl border border-slate-200">
                                <p class="font-bold text-slate-800 uppercase text-xs mb-2">CV (Curriculum Vitae) *</p>
                                <input type="file" name="cv_document" class="w-full text-sm cursor-pointer" required>
                            </div>
                            <div class="pt-8 flex justify-between items-center">
                                <a href="{{ route('user.agent.create', ['step' => 2]) }}"
                                    class="text-slate-400 font-bold underline">← Retour</a>
                                <button class="bg-blue-600 text-white font-bold py-4 px-12 rounded-xl shadow-xl">Continuer
                                    →</button>
                            </div>
                        </form>

                        {{-- ÉTAPE 4 --}}
                    @elseif($step == 4)
                        <div class="text-center space-y-10 py-10">
                            <div class="bg-green-100 p-6 rounded-2xl border-2 border-dashed border-green-200">
                                <p class="text-green-700 font-black text-xl italic">DOSSIER PRÊT ✅</p>
                            </div>
                            <form method="POST" action="{{ route('user.agent.store') }}">
                                @csrf
                                <div class="flex flex-col items-center gap-6">
                                    <button
                                        class="w-full bg-slate-900 hover:bg-black text-white font-black py-6 rounded-2xl shadow-2xl transition-all">
                                        ENVOYER MA CANDIDATURE
                                    </button>
                                    <a href="{{ route('user.agent.create', ['step' => 3]) }}"
                                        class="text-slate-400 font-bold underline text-sm italic">← Modifier documents</a>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
@endsection
