@extends('layouts.app')

@section('content')
    <div class="min-h-screen w-full flex flex-col lg:flex-row font-sans antialiased bg-slate-50">

        {{-- CONTENU GAUCHE --}}
        <div class="w-full lg:w-[60%] flex flex-col p-6 md:p-12 lg:p-20 overflow-y-auto">

            <div class="max-w-3xl w-full mx-auto">

                {{-- HEADER --}}
                <div class="flex justify-between items-center mb-12">
                    <h2 class="text-3xl font-black text-slate-900">Postuler Agent</h2>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="bg-slate-900 text-white px-5 py-2 rounded-xl font-bold">
                            Logout
                        </button>
                    </form>
                </div>

                {{-- STEPS --}}
                <div class="flex justify-between mb-10">
                    @foreach ([1, 2, 3, 4] as $i)
                        <div class="text-center">
                            <div
                                class="w-10 h-10 rounded-xl flex items-center justify-center font-bold
                            {{ $step == $i ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-500' }}">
                                {{ $i }}
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- STEP CONTENT --}}
                <div class="bg-white p-8 rounded-3xl border">

                    {{-- STEP 1 --}}
                    @if ($step == 1)
                        <form method="POST" action="{{ route('user.agent.step') }}" class="space-y-5">
                            @csrf
                            <input type="hidden" name="step" value="1">

                            <input type="text" value="{{ auth()->user()->name }}" readonly
                                class="w-full p-4 rounded-xl bg-slate-100">

                            <input type="email" value="{{ auth()->user()->email }}" readonly
                                class="w-full p-4 rounded-xl bg-slate-100">

                            <input type="text" name="phone" placeholder="Téléphone"
                                class="w-full p-4 rounded-xl bg-slate-100" required>

                            <input type="number" name="age" placeholder="Age"
                                class="w-full p-4 rounded-xl bg-slate-100" required>

                            <button class="w-full bg-blue-600 text-white py-4 rounded-xl">
                                Continuer →
                            </button>
                        </form>
                    @endif


                    {{-- STEP 2 --}}
                    @if ($step == 2)
                        <form method="POST" action="{{ route('user.agent.step') }}" class="space-y-5">
                            @csrf
                            <input type="hidden" name="step" value="2">

                            <select name="experience" class="w-full p-4 rounded-xl bg-slate-100" required>
                                <option value="">Expérience</option>
                                <option>Débutant</option>
                                <option>Intermédiaire</option>
                                <option>Expert</option>
                            </select>

                            <select name="availability" class="w-full p-4 rounded-xl bg-slate-100" required>
                                <option value="">Disponibilité</option>
                                <option>Temps plein</option>
                                <option>Matin</option>
                                <option>Après-midi</option>
                            </select>

                            <textarea name="motivation" class="w-full p-4 rounded-xl bg-slate-100" placeholder="Motivation..."></textarea>

                            <div class="flex justify-between">
                                <a href="{{ route('user.agent.create', ['step' => 1]) }}">← Retour</a>
                                <button class="bg-blue-600 text-white px-6 py-3 rounded-xl">
                                    Continuer →
                                </button>
                            </div>
                        </form>
                    @endif


                    {{-- STEP 3 --}}
                    @if ($step == 3)
                        <form method="POST" action="{{ route('user.agent.step') }}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="step" value="3">

                            <input type="file" name="cv" class="mb-6">

                            <div class="flex justify-between">
                                <a href="{{ route('user.agent.create', ['step' => 2]) }}">← Retour</a>
                                <button class="bg-blue-600 text-white px-6 py-3 rounded-xl">
                                    Continuer →
                                </button>
                            </div>
                        </form>
                    @endif


                    {{-- STEP 4 --}}
                    @if ($step == 4)
                        <div class="text-center space-y-6">
                            <p class="text-green-600 font-bold text-xl">Dossier prêt ✅</p>

                            <form method="POST" action="{{ route('user.agent.store') }}">
                                @csrf
                                <button class="w-full bg-slate-900 text-white py-4 rounded-xl">
                                    Envoyer
                                </button>
                                <a href="{{ route('user.agent.create', ['step' => 3]) }}">← Retour</a>
                            </form>
                        </div>
                    @endif

                </div>

            </div>
        </div>

        {{-- IMAGE DROITE --}}
        <div class="relative w-full lg:w-[40%] h-80 lg:h-screen lg:sticky lg:top-0 bg-slate-900 overflow-hidden">

            <img src="{{ asset('images/agent.png') }}" class="absolute inset-0 w-full h-full object-cover opacity-40">

            <div class="absolute inset-0 flex flex-col justify-end p-10 text-white">
                <span class="w-12 h-1.5 bg-blue-500 mb-6 rounded-full"></span>

                <h2 class="text-5xl font-black">
                    Devenir <br>
                    <span class="text-blue-400 italic">Agent</span>
                </h2>

                <p class="text-slate-300 mt-4">
                    Rejoignez notre équipe et gérez les parkings intelligents.
                </p>
            </div>
        </div>

    </div>
@endsection
