@extends('layouts.app')

@section('content')
    <div class="min-h-screen flex flex-col lg:flex-row bg-slate-50">


        <div class="w-full lg:w-[60%] flex items-center justify-center p-8 md:p-16">

            <div class="w-full max-w-xl">

                @if (session('success'))
                    <div class="bg-green-50 text-green-700 p-4 rounded-xl mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-green-50 text-red-700 p-4 rounded-xl mb-6">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="flex items-center gap-4 mb-12">
                    <div
                        class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center text-white font-black text-xl">
                        P
                    </div>
                    <div>
                        <h1 class="text-2xl font-black text-slate-900">ParkSys</h1>
                        <p class="text-xs text-slate-400 uppercase tracking-widest">Bienvenue</p>
                    </div>
                </div>


                <h2 class="text-4xl font-black text-slate-900 mb-3">
                    Choisissez votre action
                </h2>
                <p class="text-slate-500 mb-10">
                    Accédez rapidement à votre besoin
                </p>


                <div class="space-y-6">


                    <a href="{{ route('register', ['type' => 'client']) }}"
                        class="block bg-white border border-slate-200 rounded-2xl p-6 hover:shadow-lg transition-all group">

                        <div class="flex items-center gap-4">
                            <div
                                class="w-14 h-14 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center text-2xl">
                                🚗
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-slate-900 group-hover:text-blue-600">
                                    Réserver une place
                                </h3>
                                <p class="text-sm text-slate-400">
                                    Trouvez une place disponible en temps réel
                                </p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('register', ['type' => 'user']) }}"
                        class="block bg-white border border-slate-200 rounded-2xl p-6 hover:shadow-lg transition-all group">

                        <div class="flex items-center gap-4">
                            <div
                                class="w-14 h-14 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center text-2xl">
                                👨‍💼
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-slate-900 group-hover:text-emerald-600">
                                    Devenir agent
                                </h3>
                                <p class="text-sm text-slate-400">
                                    Rejoignez notre équipe de gestion
                                </p>
                            </div>
                        </div>
                    </a>

                </div>


                <p class="text-center text-sm text-slate-400 mt-10">
                    Déjà un compte ?
                    <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:underline">
                        Se connecter
                    </a>
                </p>

            </div>
        </div>

        <div class="hidden lg:block w-[40%] relative bg-slate-900">
            <img src="https://images.unsplash.com/photo-1506521781263-d8422e82f27a?w=1200&q=80"
                class="absolute inset-0 w-full h-full object-cover opacity-50">

            <div class="absolute inset-0 bg-gradient-to-l from-slate-900 via-transparent to-transparent"></div>

            <div class="absolute bottom-12 left-12 text-white max-w-sm">
                <span class="w-12 h-1.5 bg-blue-500 mb-6 block rounded-full"></span>

                <h2 class="text-5xl font-black leading-tight mb-4">
                    Parking <br>
                    <span class="text-blue-400 italic">Intelligent</span>
                </h2>

                <p class="text-slate-300">
                    Réservez facilement votre place et gérez vos véhicules en toute simplicité.
                </p>
            </div>
        </div>

    </div>
@endsection
